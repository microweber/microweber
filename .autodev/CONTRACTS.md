# Agent Contracts

> Last updated: 2026-05-13
> Read this file before sending any email, message, or notification.

---

## Human Contacts

| Name         | Role                  | Email               | Channel | Notes                                                                                                  |
|--------------|-----------------------|---------------------|---------|--------------------------------------------------------------------------------------------------------|
| Peter Ivanov | Project owner (human) | boksiora@gmail.com  | email   | Contact ONLY when an escalation threshold below is met. Do not include in routine session traffic.     |

---

## Agent Contacts

| Agent name      | Role                          | Address                        | Channel | Accepts                                                                 |
|-----------------|-------------------------------|--------------------------------|---------|-------------------------------------------------------------------------|
| agent-pm        | Product manager / dispatcher  | agent-pm@emailpwd.com          | email   | `[DISPATCH] AI-XXX`, status pings, deferred-pool follow-up requests.    |
| agent-test      | Test agent / audit dispatch   | agent-test@emailpwd.com        | email   | Audit findings, sub-bug dispatches, post-fix verification requests.     |
| tester-agent-1  | Browser test runner           | (via agent-test's channel)     | email   | Routes through agent-test — do not contact directly.                    |
| agent-designer  | Designer (UX + Drunk personae) | agent-designer@emailpwd.com   | email   | Design audits, design specs, UI/UX direction. Sourced 2026-05-16 via human dispatch task-f4bf24. Workspace at `/home/headless/Documents/GitHub/designer-agent/` (read-accessible). |
| dev-a1 / agent-a1 | Me (this agent)             | dev-a1@emailpwd.com            | email   | Replies to dispatches, ship reports, deferred-AC suggestions.           |

*Address format: `user@example.com` for SMTP; `alias@local` for local MCP routing. The `default` account name in `mcp__zerolib-email__send_email` is the canonical outbound channel.*

---

## Routing Rules

| Message type                                  | Send to                         | Channel | Condition                                                                                |
|-----------------------------------------------|---------------------------------|---------|------------------------------------------------------------------------------------------|
| Ship report after dispatched task             | agent-pm + Cc agent-test        | email   | After every `[DISPATCH] AI-XXX` ship. Subject `Re: <original subject>`.                  |
| Audit-finding response (sub-bug ship)         | agent-test + Cc agent-pm        | email   | After shipping a tester-dispatched sub-bug. Subject `Re: <original subject>`.            |
| Deferred-AC follow-up suggestions             | agent-pm                        | email   | Inline within the ship report — do not send a separate email.                            |
| Routing concern flag (e.g. duplicate ack)     | agent-pm                        | email   | Inline within the next ship report — flag once, not repeatedly.                          |
| Pure-ack from PM with no question             | (do not reply)                  | —       | If PM acks a ship and explicitly signals "Standing by", DO NOT send an ack-of-ack reply. |
| Status update without substantive change      | (do not send)                   | —       | TODO.md update is sufficient.                                                            |
| Blocker requiring human input                 | Peter Ivanov                    | email   | Only when all 3 escalation thresholds below are met.                                     |

---

## Escalation Thresholds

Contact the human (Peter Ivanov / boksiora@gmail.com) only when ALL of the following are true:

- [ ] The task cannot proceed without information only the human can provide.
- [ ] At least one automated retry / agent-routed clarification has been attempted and failed.
- [ ] The blocker has been present for more than **N = 60 minutes**.

If any of the three is unmet, route through agent-pm or agent-test instead.

---

## Reply Rules

- **Subject prefixes:** preserve the original subject + `Re:` prefix. Don't add `[needs input]` etc. unless the email genuinely needs explicit human attention.
- **Reply with `[needs input]`** when blocked and an agent route exists.
- **Reply with `[task]`** only when delegating actual work to another agent (rare from dev-a1).
- **Do NOT reply to confirm task completion** — `[ACK]` chains are the routing concern flagged in JOURNAL row 13 + the session-level routing notes (tasks 44d011 / a05c6d / ac6f65).
- **Do NOT CC anyone not already in the thread** unless adding a Cc materially helps routing (e.g. tester-agent-1 needs to know about a fix that affects their next audit).

---

## Canonical Dev Environment Credentials

These are the ONLY admin credentials that should be used for local dev, Dusk tests, and Playwright runs.
Mixing passwords across agents causes Dusk login failures and inconsistent verification results.

| Surface | Email | Password | Source |
|---------|-------|----------|--------|
| Admin login (`/admin/login`) | `admin@admin.com` | **`admin`** | `.env.dusk` + `ResolvesWorkflowEnvironment` default |
| Dusk `DUSK_ADMIN_EMAIL` | `admin@admin.com` | — | `.env.dusk` |
| Dusk `DUSK_ADMIN_PASSWORD` | — | **`admin`** | `.env.dusk` |

**Reset command** (if the DB password has drifted):
```
php artisan tinker --execute="App\Models\User::where('email','admin@admin.com')->first()->update(['password'=>bcrypt('admin')])"
```

**Playwright field injection quirk** (Filament toggle-mask on password field — tester discovery 2026-05-22):
Use `.type()` for the email field; for the password field, inject via `page.evaluate(el => el.value = 'admin', handle)` to bypass the Filament visibility-toggle `input` event listener that resets `.fill()` input.

---

## Notable Routing Patterns (observed 2026-05-13)

- **Duplicate dispatches:** agent-test and agent-pm sometimes both dispatch the same audit/ack within seconds. Treat as a single unit of work; close the second with a one-line pointer to the first reply. Flagged 3 times today (task-44d011, task-a05c6d, task-ac6f65); PM has committed to consolidating future dispatches via `[DISPATCH] AI-XXX` format.
- **Audit-mission tickets vs dev tickets:** several `[Audit/agent-test]` tickets (AI-275, AI-280, AI-261, AI-258, AI-259, AI-257, AI-256, AI-255) appear in PM's dev queue but their deliverable is a report, not code. Filter by JIRA Assignee + ticket type (`Task`/`Bug`) before considering them dev candidates.
- **Deferred-AC follow-up tickets:** large multi-AC tickets (AI-265, AI-269/272, AI-274, AI-246, AI-293, AI-209) commonly ship as bounded-slice first + AI-XXXa/b/c follow-ups. Don't ship the follow-up code without an explicit `[DISPATCH]` for the follow-up ticket.
