# Work Documentation Johannes Rödel

This website contains the documentation for all my work I did at ETES. 

Right now it's split between:

**`Edira`**: documentation for the issues I worked on in Edira

**`Education`**: documenation for the online courses I did to prepare myself for my work at ETES

**`Setup`**: documentation for the setups that are used in the company

The provided documentation is a MkDocs Collection that is hosted in a GitLab repository with a CI/CD pipeline as a GitLab Page to offer a better GUI.

---

## [Link GitLab Source Repository](https://git.etes.de/jroedel-work/work-documentation.git)

The documentation supports a search function to quickly search for specific words.

If you want to run it locally on your machine follow the instructions down below. 

---

## Project Structure 

```
.
├── mkdocs_simple_blog/         # Simple-blog src files for customization
├── scripts/                    # Simple-blog script files to update template after changes
├── src/                        # All documentation files
│   ├── setup/                  # Instructions setup needed tools for ETES
│   │   ├── edira-dev-env/      # Edira development environment setup
│   │   │   ├── docs/           # .md documentation files
│   │   │   └── src             # Other files
│   │   ├── .../                # Additional setup guides
│   ├── edira/                  # Documentation for Edira and Issues
│   │   ├── project-breakdown/  # Explanations like folder structure, laraval/livewire features, etc.
│   │   │   ├── docs/           # .md documentation files
│   │   │   └── src             # Other files
│   │   ├── issue-XY/           # Specific issue
│   │   │   ├── docs/           # .md documentation files
│   │   │   └── src             # Other files
│   │   ├── .../                # Additional issues
│   ├── education/              # All educational course for preparations to work at ETES
│   │   ├── etes-edu/           # All internal course made by ETES
│   │   │   ├── BD-XY/          # Specific course
│   │   │   │   ├── docs/       # .md documentation files
│   │   │   │   └── src         # Source files from exercises
│   │   │   ├── .../            # Additional internal courses
│   │   ├── laracast/           # All laracast courses
│   │   │   ├── laravel/        # Specific laravel course
│   │   │   │   ├── docs/       # .md documentation files
│   │   │   │   └── src         # Source files from the exercises  
│   │   ├── .../                # Additional laracast courses
│   ├── .../                    # Additional topics that will be implemented in the future        
│   └── index.md                # Home Page MkDocs 
├── .gitignore                  # Local files that don't get pushed to the remote repository
├── .gitlab-ci.yml              # CI pipeline to build GitLab Page for MkDocs
├── mkdocs.yml                  # MkDocs config
└── README.md                   # Repository overview & setup instructions
```

---

## **Getting Started**

### Running MkDocs Locally on a Non-Arch Distribution

With the following steps you can run the MkDocs locally.

**1. Clone This Repository**

```bash
git clone https://git.etes.de/jroedel-work/work-documentation.git
cd work-documentation
```
**2. Install Python**

Ensure Python and pip are installed on your system. If Python is not installed on your machine follow the official instructions for your system.

```bash
python --version
pip --version
```

**3. Install MKDocs With Plugins**

To install mkdocs and the required plugins for this project run:

```bash
pip install mkdocs mkdocs-simple-blog
```

---

### Running MkDocs on an Arch Distribution

While installing pip for the first time, you will encounter the error: externally-managed-environment. You are getting this error because Arch Linux has a different way of managing Python packages than other Linux distributions. Arch Linux uses Pacman as its package manager, which installs Python packages system-wide and ensures that they are compatible with the rest of the system.

To resolve this error, remove the `EXTERNALLY-MANAGED` file. To remove this file, run:

```bash
sudo rm -rf /usr/lib/pythonX.XX/EXTERNALLY-MANAGED
```

Then run the commands to install for MkDocs from above.
I use MkDocs for a GUI interface to read the documentation for this project. Use these commands to install it:

### Serving Documentation Locally

```bash
mkdocs serve
```

The documentation will be available at:

[localhost:8000](http://127.0.0.1:8000)

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