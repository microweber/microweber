# Microweber MCP — Bruno collection

A ready-to-import [Bruno](https://www.usebruno.com/) collection
for driving every method on the Microweber MCP server without
writing curl by hand.

## Loading

In Bruno: **Open Collection → select this directory**
(`docs/mcp/bruno-microweber-mcp/`). The seven `.bru` request
files load as a numbered list, and the `environments/Local.bru`
file becomes the default environment.

## Configuring the bearer token

Bruno → environment dropdown → **Local** → edit. Replace the
`bearer_token` placeholder with a real token issued via:

```bash
php artisan ai:mcp:client:create \
    --name="Bruno Local" \
    --scopes=mcp:access \
    --tools='*' --modules='*'
```

The plain-text token is printed once on stdout. Paste it into
the Bruno environment's `bearer_token` var.

## Requests

| # | Method                           | What it covers                                              |
|---|----------------------------------|-------------------------------------------------------------|
| 1 | `initialize`                     | Handshake. Echoes back the client-advertised protocolVersion. |
| 2 | `ping`                           | Liveness probe. Returns `result: {}`.                       |
| 3 | `notifications/initialized`      | Post-handshake notification. Server returns HTTP 204.       |
| 4 | `tools/list`                     | Enumerate the catalog allowed for the calling token.        |
| 5 | `tools/call` → `content.lookup`  | Read-only search representative.                            |
| 6 | `tools/call` → `settings.read`   | Read-only settings representative.                          |
| 7 | Batch                            | JSON-RPC 2.0 §6 — three envelopes, three responses in order. |

## Other transports

The same JSON envelopes work over the stdio transport launched
by `php artisan ai:mcp:serve --token=...` — Bruno is HTTP-only,
but every `.bru` body in this collection can be `cat`-ed into
the stdio command for the same end-to-end check.
