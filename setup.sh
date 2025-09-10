# Create virtual environment and install dependencies
python -m venv venv
source venv/bin/activate
pip install -r requirements.txt --no-cache-dir

# Run installation script and serve documentation
python scripts/install_local.py 
mkdocs serve