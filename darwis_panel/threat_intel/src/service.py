import os
from time import sleep
import time
from main_logger import logger


def main():
    root_dir = os.path.dirname(os.getcwd())
    last_config_error_time = None

    main_config_path = f"{root_dir}/config/app.conf"

    while True:
        try:
            if os.path.exists(main_config_path):
                from modules.get_info import InfoApi

                InfoApi.run()
            else:
                if (
                    last_config_error_time is None
                    or time.time() - last_config_error_time > (3600 * 12)
                ):
                    last_config_error_time = time.time()
                    logger.error("Main config yet to be configured")
        except Exception as e:
            logger.exception(e)
            sleep(1800)

        sleep(300)


if __name__ == "__main__":
    main()
