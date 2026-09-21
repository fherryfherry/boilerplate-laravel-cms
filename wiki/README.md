# Project Wiki

This wiki is the persistent memory for AI agents working in this project. It records project structure, validated knowledge, technical decisions, workflows, and recent changes.

## Agent Usage Workflow

Before answering questions or changing the project:

1. Read this file.
2. Read `wiki/LOG.md` for recent changes.
3. Search `wiki/knowledges/` for relevant project knowledge.
4. Use the wiki together with direct code inspection. Do not rely on memory alone.

When new validated knowledge is discovered:

1. Create or update a specific knowledge page in `wiki/knowledges/`.
2. Update the Knowledge Index in this file.
3. Record the change in `wiki/LOG.md`.

When the project changes:

1. Record the change in `wiki/LOG.md`.
2. Update this file if the project structure or knowledge index changed.
3. Add or update knowledge pages when the change teaches a reusable project fact, pattern, fix, decision, or workflow.

## Project Map

Document the current project structure here as it becomes known.

## Wiki Structure

- `wiki/README.md` - Main project memory index and project map.
- `wiki/LOG.md` - Append-only log of project changes and agent activity.
- `wiki/knowledges/` - Specific validated knowledge pages.

## Knowledge Index

| File | Topic | Summary | Last Updated |
| --- | --- | --- | --- |
| _No knowledge pages yet._ | _N/A_ | _Add entries when knowledge pages are created._ | _N/A_ |

## Knowledge Page Template

Use this structure for files in `wiki/knowledges/`:

```md
# Topic Title

## Context

Explain when this knowledge applies.

## Validated Knowledge

State the confirmed project fact, workflow, decision, or fix.

## How To Apply

Describe the practical steps or rule for future agents.

## Related Files

- `path/to/file`

## Last Verified

YYYY-MM-DD
```
