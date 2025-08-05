# Git

Git allows you to take snapshots of your project over time. Before version control, developers often copied entire project folders as backups with names like project-2023-06-01. This manual method was error-prone and inefficient.

Git automates this process, letting you save incremental snapshots (commits) with descriptive messages, making it easy to track and revert changes.

## Initial Git Configuration

After installing Git, you need to configure your user information once per computer:

`git config --global user.name "Your Name"`

`git config --global user.email "you@example.com"`

This information identifies who makes changes in a project, which is important when collaborating with others.

### Code Editor for Commit Messages

You can also set your preferred code editor for Git commit messages:

`git config --global core.editor "subl -w"`

Replace "subl -w" with your editor of choice, such as vim, emacs, or nano.

---

## First Commit 

1. Initialize a new Git repository:

    ```
    git init
    ```

    This creates a `.git` directory where Git stores all version control data.

2. Add files to staging area:

    ```
    git add .
    ```

3. Check the status:

    ```
    git status
    ```

4. Commit the staged changes with a message:

    ```
    git commit -m "Initial commit"
    ```

    This saves the snapshot permanently in the repository.

---

## Git Diff

If you modify a file after adding it to the staging area, Git will show that the file has changes not yet staged. You can view the differences with:

`git diff`

This shows what has changed since the last snapshot. If you modify files after committing but before adding again, those changes won't be included in the last commit until you stage and commit them.

---

## Fixing and Amending Commits

When working with Git, it's common to realize after making a commit that you forgot something, made a typo, or introduced a bug. Here's how to handle such situations.

### Scenario

Suppose you committed a change with a typo in the message or content:

- Initialize Git and add your files to the staging area.

- Commit with a message, e.g., "frist draft of file" (typo intended).

- Use git log to see your commit and its unique hash.

If you haven't pushed this commit yet and want to fix the typo without creating a new commit, you have two main options.

### Reset and Recommit

- Use `git reset` to undo the commit.

- A `soft reset` (git reset --soft <commit>) resets the commit but keeps your changes staged.

- A `hard reset` (git reset --hard <commit>) resets the commit and discards any changes after it.

Be very careful with `git reset --hard` as it erases uncommitted changes.

After resetting, you can fix your typo and recommit.

### Amend the Commit 

If you want to keep the commit but fix the typo:

1. Stage your corrected changes.
2. Run `git commit --amend`.
3. Your editor will open with the previous commit message; fix the message if needed.
4. Save and exit.

This updates the previous commit with your new changes and message.

### When To Use Each Option

If you've already pushed the commit or shared it with others, it's better to create a new commit fixing the mistake.

If the commit is local and not shared, amending or resetting is cleaner.

## When to Commit

A common question is: when should you create a new commit?

A good guideline is to commit when you hit a milestone or complete a feature. For example:

- Implement an authentication layer: code it, test it, then commit with a message like "Add authentication layer".

- Add a reporting feature: once done, commit with "Add reporting feature".

Before committing, always check your status to ensure only relevant files are staged. Avoid committing unrelated changes like configuration tweaks in the same commit.

--- 

## Longer Commit Messages

If your commit message needs more detail, omit the `-m` flag when committing:

`git commit`

This opens an editor where you can write a multi-line commit message. The first line is a brief summary, followed by a blank line, then a detailed description.

When pushed to services like GitHub, the first line is shown in the commit list, making it concise and readable.

---

## Branching

Imagine you're working on a big feature with many changes, but halfway through, you discover a critical bug on your production site that needs an immediate fix. Since your current code is in flux and not ready for production, you can't just push your changes. Instead, you switch back to the master branch, fix the bug, and push that fix without the unfinished feature interfering.

Branches allow you to isolate your work so that changes on one branch don't affect others until you're ready to merge.

### Workflow Branch to Fix a Bug

1. Create a new branch for your work:

    `git checkout -b issue-123`

2. Fix the bug or add the feature, then commit:

    `git add somefix.txt`
    `git commit -m "Fix bug"`

3. Switch back to master and merge:

    `git checkout master`
    `git merge issue-123`

4. Delete the branch:

    `git branch -d issue-123`
