# Sceleton MkDocs Project

This repository contains a sceleton project structure that allows you to costumize the theme of the simple-blog fork for mkdocs. 

---

## Project Structure 

```
.
├── mkdocs_simple_blog/         # Simple-blog src files for customization
├── scripts/                    # Simple-blog script files to update template after changes
├── src/                        # All documentation files
│   ├── page01/                 # Files for menu point 1
│   │   ├── docs/               # .md documentation files
│   │   └── src                 # Other files
│   ├── page02/                 # Files for menu point 2
│   │   ├── docs/               # .md documentation files
│   │   └── src                 # Other files     
│   └── index.md                # Home Page MkDocs 
├── .gitignore                  # Local files that don't get pushed to the remote repository
├── .gitlab-ci.yml              # CI pipeline to build GitLab Page for MkDocs
├── mkdocs.yml                  # MkDocs config
├── README.md                   # Repository overview & setup instructions
├── requirements.txt            # Python packages that get installed by the setup.sh script
├── setup.py                    # Python setup script
└── setup.sh                    # Shell setup script
```

---

## **Getting Started**

### Setup

The following steps setup everything needed to run the setup scripts.

**1. Clone This Repository**

```bash
git clone https://github.com/velveneer/mkdocs-sb-sceleton.git

cd mkdocs-sb-sceleton
```

**2. Install Python**

Ensure Python and pip are installed on your system. If Python is not installed on your machine follow the official instructions for your system.

```bash
python --version
pip --version
```

**3. Activate Virtual Environment**

```bash
python3 -m venv .venv

source .venv/bin/activate
```

If you're running into permission problems, check if your user has the correct permissions for this folder:

```bash
ls -ld .venv
```

If you're not seeing your user run:

```bash
deactivate 2>/dev/null || true

sudo rm -rf .venv

python -m venv .venv

source .venv/bin/activate
```

**4. Running Setup Script**

To install mkdocs and the required plugins for this project run:

```bash
./setup.sh
```

The documentation will be available at:

[localhost:8000](http://127.0.0.1:8000)

---

### Theme Customization

This MkDocs Documenation uses [Fernando Celmers MkDocs Theme](https://github.com/FernandoCelmer/mkdocs-simple-blog). 

I've made some customizations inside the `mkdocs_simple_blog/` folder to fit the needs of this documentation. If you want to do this yourself follow these steps:

**1. Create your virtual environment**
   
```bash
python -m venv venv
```

**2. Activate the virtual environment**
   
```bash
source venv/bin/activate
```

**3. Install the necessary requirements to be able to test the application**
   
```bash
pip install -r requirements.txt --no-cache-dir
```

**4. Make your changes as desired in the ./mkdocs_simple_blog folder**
   
```bash
ls mkdocs_simple_blog
```

**5. Run the script that creates and installs the local package to build your new theme**

```bash
python scripts/install_local.py 
```

**6. Run your MkDocs site**

```bash
mkdocs serve
```

## Contribution 

If you want to contribute for example to the `setup` documentation, feel free to create your own branch and merge request <3