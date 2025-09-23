#!/usr/bin/env python3
from __future__ import annotations

import sys
import subprocess
from pathlib import Path
from shutil import rmtree

CONTEXT = Path.cwd()

def sh(*args: str) -> None:
    """Run a command and fail loud if it errors."""
    subprocess.check_call(list(args))

def pip_run(*pip_args: str) -> None:
    """Run pip in the current interpreter/venv."""
    sh(sys.executable, "-m", "pip", *pip_args)

def install_package() -> None:
    # Prefer reading version safely; or just pick the built artifact.
    last_version_file = CONTEXT / "LAST_VERSION"
    if last_version_file.exists():
        version = last_version_file.read_text().strip().replace("-", ".")
        dist_file = CONTEXT / "dist" / f"mkdocs_simple_blog-{version}.tar.gz"
    else:
        # fallback: first matching file
        matches = sorted((CONTEXT / "dist").glob("mkdocs_simple_blog-*.tar.gz"))
        if not matches:
            raise FileNotFoundError("No built sdist found in ./dist")
        dist_file = matches[-1]

    # Uninstall old then install new
    pip_run("uninstall", "-y", "mkdocs-simple-blog")
    pip_run("install", "--no-cache-dir", str(dist_file))

def build_package() -> None:
    sh(sys.executable, "-m", "build")

def install_requirements() -> None:
    pip_run("install", "--upgrade", "pip")
    pip_run("install", "build")

def remove_tree(path: Path) -> None:
    if path.exists():
        rmtree(path)

if __name__ == "__main__":
    for folder in (CONTEXT / "dist", CONTEXT / "mkdocs_simple_blog.egg-info"):
        remove_tree(folder)

    install_requirements()
    build_package()
    install_package()
