import subprocess
import os

# --- XAMPP CONFIGURATION ---
# Default XAMPP path. Change this if you installed XAMPP elsewhere.
MYSQL_BIN_PATH = r"C:\xampp\mysql\bin" 

DB_USER = "root"
DB_PASSWORD = ""
DB_NAME = "GamePlanner" 
SQL_FILE = "database/database.sql"

def run_command(command):
    try:
        # We use shell=True to handle the redirection symbols (< and >)
        subprocess.run(command, shell=True, check=True, capture_output=True)
        return True
    except subprocess.CalledProcessError as e:
        print(f"❌ Error: {e.stderr.decode().strip()}")
        return False

def push():
    """Exports the local XAMPP database to the .sql file"""
    print(f"🚀 Exporting '{DB_NAME}' to '{SQL_FILE}'...")
    dump_path = os.path.join(MYSQL_BIN_PATH, "mysqldump.exe")
    # Command: mysqldump -u user db_name > file.sql
    cmd = f'"{dump_path}" -u {DB_USER} {DB_NAME} > {SQL_FILE}'
    
    if run_command(cmd):
        print("✅ Success! Your changes are now saved to the file.")

def pull():
    """Imports the .sql file into the local XAMPP database"""
    print(f"📥 Importing '{SQL_FILE}' into '{DB_NAME}'...")
    mysql_path = os.path.join(MYSQL_BIN_PATH, "mysql.exe")
    # Command: mysql -u user db_name < file.sql
    cmd = f'"{mysql_path}" -u {DB_USER} {DB_NAME} < {SQL_FILE}'
    
    if run_command(cmd):
        print("✅ Success! Your local database is now up to date.")

if __name__ == "__main__":
    choice = input("Do you want to (1) PUSH changes to file or (2) PULL changes from file? [1/2]: ")
    
    if choice == "1":
        push()
    elif choice == "2":
        pull()
    else:
        print("Invalid choice. Please enter 1 or 2.")