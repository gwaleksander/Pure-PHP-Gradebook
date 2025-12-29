import os
import shutil

SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
BASE_DIR = os.path.abspath(os.path.join(SCRIPT_DIR, "..", ".."))
OUTPUT_DIR = os.path.join(SCRIPT_DIR, "kopie_txt")
os.makedirs(OUTPUT_DIR, exist_ok=True)
EXTENSIONS = {".php", ".sql", ".css"}

for root, dirs, files in os.walk(BASE_DIR):

    if root.startswith(OUTPUT_DIR):
        continue

    for file in files:

        name, ext = os.path.splitext(file)

        if name == "szkola":
            continue

        if ext.lower() in EXTENSIONS:
            full_path = os.path.join(root, file)

            rel_path = os.path.relpath(full_path, BASE_DIR)

            safe_name = rel_path.replace(os.sep, "_")

            dest_name = safe_name + ".txt"
            dest_path = os.path.join(OUTPUT_DIR, dest_name)

            shutil.copyfile(full_path, dest_path)

            print(f"Skopiowano: {full_path} → {dest_path}")

print("Zakończono kopiowanie.")
