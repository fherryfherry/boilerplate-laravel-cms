# Project Log

This is the append-only activity log for project changes, knowledge updates, decisions, fixes, and notable agent actions.

Use this format:

```md
## [YYYY-MM-DD HH:mm] type | title

- Summary: What changed or was learned.
- Files: Relevant files or directories.
- Notes: Important context for future agents.
```

Supported initial types: `setup`, `change`, `knowledge`, `decision`, `fix`, `note`.

## [2026-05-28 16:38] setup | Persistent project wiki installed

- Summary: Created the initial persistent project wiki structure.
- Files: `AGENTS.md`, `wiki/README.md`, `wiki/LOG.md`, `wiki/knowledges/`.
- Notes: Future agents should read `wiki/README.md` before answering questions or changing the project.
