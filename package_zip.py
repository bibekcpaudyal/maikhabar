# package_zip.py - Zipper for Municipality News Portal
import os
import zipfile

def create_zip():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    zip_path = os.path.join(base_dir, 'municipality_news_portal.zip')

    print(f"Creating zip file at {zip_path}...")

    with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(base_dir):
            for file in files:
                if file.endswith('.zip') or file == 'package_zip.py':
                    continue
                file_path = os.path.join(root, file)
                arcname = os.path.relpath(file_path, base_dir)
                zipf.write(file_path, arcname)
                print(f"Added: {arcname}")

    print("Zip file created successfully!")

if __name__ == '__main__':
    create_zip()
