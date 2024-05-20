import logging
from logging.handlers import RotatingFileHandler
import os


def get_logger():
    root_dir = os.path.dirname(os.getcwd())

    logs_dir = os.path.join(root_dir, "logs")

    if not os.path.exists(logs_dir):
        os.makedirs(logs_dir)

    log_formatter = logging.Formatter(
        "[%(levelname)s] %(asctime)s:{%(pathname)s:%(lineno)d} - %(message)s\n---------", "%d/%m/%y %H:%M:%S")

    my_handler = RotatingFileHandler(
        f'{root_dir}/logs/app.log', maxBytes=5*1024*1024, backupCount=5)
    my_handler.setFormatter(log_formatter)

    app_log = logging.getLogger(__name__)
    app_log.addHandler(my_handler)
    app_log.setLevel(logging.INFO)

    return app_log


logger = get_logger()
