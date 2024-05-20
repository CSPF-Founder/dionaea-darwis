import shutil
from time import sleep
import config
from utils import hash_utils
from models.enums import VerdictStatus
from main_logger import logger

import os
import requests


class InfoApi(object):

    @classmethod
    def get_file_hash_code(cls, file_path):
        hash_code = hash_utils.file_to_sha256_hash(file_path)

        return hash_code

    @classmethod
    def send_get_request(cls, file_hash_code, file_name, file_path):
        logger.info(f"Checking file {file_hash_code}")
        headers = {
            "Authorization": "Bearer {}".format(config.config["THREAT_INTEL_API_KEY"])
        }

        response = None
        try:
            request_url = config.config[
                "THREAT_INTEL_API_URL"
            ] + "/hash/query/{}".format(file_hash_code)
            response = requests.get(request_url, headers=headers, verify=False)
        except Exception as e:
            logger.error(f"Exception - {file_hash_code}")
            logger.exception(e)
            sleep(120)
            return

        if response is None:
            logger.error(f"No response received for hash query {file_hash_code}")
            sleep(120)
            return

        tmp_hash_checked_path = config.config["TMP_HASH_CHECKED_DIR"] + file_name
        final_file_path = config.config["PROCESSED_FILES_DIR"] + file_name

        # move the file
        shutil.move(file_path, tmp_hash_checked_path)

        if response.status_code != 200:
            logger.error(
                f"Non-200 status code '{response.status_code}' received for {file_hash_code}"
            )
            logger.error(str(response.text))
            sleep(120)
            return

        result = response.json()

        verdict = int(result.get("verdict"))
        log_incident_id = result.get("log_incident_id")

        if verdict == VerdictStatus.CLEAN:
            logger.info(f"Uploading file {file_hash_code}")
            try:
                # upload file - send post request
                files = {"input_file": open(tmp_hash_checked_path, "rb")}
                values = {"log_incident_id": log_incident_id}
                post_response = requests.post(
                    config.config["THREAT_INTEL_API_URL"] + "/file/upload",
                    files=files,
                    data=values,
                    headers=headers,
                    verify=False,
                )

                if post_response is None:
                    sleep(120)
                    logger.error(
                        f"File Upload: No response received for {file_hash_code}"
                    )
                elif response.status_code != 200:
                    sleep(120)
                    logger.error(
                        f"File Upload: Non-200 status code '{response.status_code}' received for {file_hash_code}"
                    )
                    logger.error(str(response.text))
                    sleep(120)
            except Exception as ex:
                logger.error(ex)
                shutil.move(tmp_hash_checked_path, final_file_path)
                sleep(120)
                return

        # os.remove(tmp_hash_checked_path)
        shutil.move(tmp_hash_checked_path, final_file_path)

    @classmethod
    def run(cls):
        file_name_list = os.listdir(config.config["SAMPLE_DIR"])

        for file_name in file_name_list:
            file_path = config.config["SAMPLE_DIR"] + file_name

            file_hash_code = cls.get_file_hash_code(file_path)

            cls.send_get_request(file_hash_code, file_name, file_path)
