from configparser import ConfigParser
import os

root_dir = os.path.dirname(os.getcwd())
config = {}
config_from_conf = ConfigParser()
config_from_conf.read(f'{root_dir}/config/app.conf')

config['MAD_DATA_DIR'] = config_from_conf.get("MAIN", 'mad_data_dir')
config['SAMPLE_DIR'] = config['MAD_DATA_DIR'] + "/malware_samples/"
config['TMP_HASH_CHECKED_DIR'] = config['MAD_DATA_DIR'] + "/tmp_hash_checked/"
config['PROCESSED_FILES_DIR'] = "/processed_files/"


config['THREAT_INTEL_API_URL'] = config_from_conf.get(
    "MAIN", 'threat_intel_api_url')
config['THREAT_INTEL_API_KEY'] = config_from_conf.get(
    "MAIN", 'threat_intel_api_key')
