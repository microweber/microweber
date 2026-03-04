---
name: webhook-developer
description: Use when developing outgoing webhooks for applications and services.
---

# Webhook Developer

## Core Workflow

1. *Analyze Requirements*: Understand the events that need to trigger webhooks and the data to be included in the payload.
2. *Design Payloads*: Create webhook payload structures following the Standard Webhooks specification.
3. *Implement Delivery Mechanism*: Set up the webhook delivery system, including HMAC signing and HTTPS enforcement.
4. *Set Up Retry Logic*: Implement retry mechanisms with exponential backoff for failed deliveries.
5. *Document Webhooks*: Create comprehensive documentation for webhook events, payloads, and delivery processes.
6. *Provide Testing Tools*: Develop tools for testing webhook deliveries and managing subscriptions.
7. *Monitor and Maintain*: Continuously monitor webhook performance and make improvements as needed.

## Reference Guide

Load the detailed guidance based on context:

| Topic | Reference | Load When |
|-------|-----------|-----------|
| Standard Webhooks | `references/standard-webhooks.md` | When designing or implementing outgoing webhooks for applications and services. |

## Constraints

### MUST DO

- Follow the [Standard Webhooks](https://github.com/standard-webhooks/standard-webhooks) specification.
- Implement HMAC signature generation for webhook security.
- Use HTTPS for all webhook deliveries.
- Set up retry logic with exponential backoff for failed deliveries.
- Document all webhook events, payloads, and delivery processes.

### MUST NOT DO

- Send webhooks over unencrypted HTTP.
- Ignore failed webhook deliveries without retrying.
- Omit documentation for webhook events and payloads.

## Output Templates

1. Documentation of webhook events and payloads.
2. Webhook delivery implementation plan.
3. Testing tools for webhook deliveries.

## Skill Resources

- [Specification](https://github.com/standard-webhooks/standard-webhooks/blob/main/spec/standard-webhooks.md)
- [Website](https://www.standardwebhooks.com/)
