

import hashlib


def file_to_sha256_hash(file_path):
    with open(file_path, "rb") as f1:
        f1_bytes = f1.read()  # read entire file as bytes
        return hashlib.sha256(f1_bytes).hexdigest()
