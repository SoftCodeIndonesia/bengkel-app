# AGENTS.md

Panduan untuk agent AI (Codex, Claude Code, dan sejenisnya) yang mengerjakan proyek ini. File ini dibaca di awal setiap sesi. Jika permintaan di percakapan bertentangan dengan file ini, berhenti dan tanyakan.

## Codebase Memory MCP — Token Optimization

## Codebase Exploration
- Use Codebase Memory MCP as the primary tool for repository discovery.
- Search the graph before reading source files.
- Prefer symbol and dependency queries over broad repository searches.
- Retrieve only the relevant source snippets needed for the task.
- Use direct file reads when the graph lacks sufficient or current information.

## Index Management
- Reuse the existing repository graph index.
- Do not perform full indexing for every task or conversation.
- Index the repository only when no valid index exists.
- Prefer incremental updates when supported.
- Ensure the active graph belongs to the current repository.

## Token Efficiency
- Use compact MCP output when supported.
- Limit search results and dependency traversal depth.
- Avoid retrieving entire files when a function or class is sufficient.
- Avoid repeated searches that return the same information.
- Do not print large source code blocks or unnecessary metadata.

## Implementation Safety
- Verify actual source code before modifying files.
- Preserve existing architecture and project conventions.
- Run targeted tests after changes.
- Keep final explanations concise.

## Priority
- Follow all existing project instructions in this AGENTS.md.
- Codebase Memory rules govern code discovery and context retrieval only.
- Never sacrifice correctness, security, or testing solely to reduce token usage.