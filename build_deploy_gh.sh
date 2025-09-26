# Create virtual environment and install dependencies
python -m venv venv
source venv/bin/activate
pip install mkdocs --no-cache-dir
pip install dist/mkdocs_simple_blog-0.2.0.tar.gz --no-cache-dir

# Build and deploy documentation to GitHub Pages
mkdocs build
mkdocs gh-deploy