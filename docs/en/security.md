# 🔐 Security & Privacy

## Access Control

Capability-based access control: `block/playergames:view` is granted to every authenticated user,
since the widget lives on shared, site-wide pages rather than inside a course.

## Privacy API

This block implements the `null_provider` interface — it holds **no personal data of its own**. All
personal data (profile, XP, streak, activity log, avatar) is owned and declared by
`local_playergames`.

## Write Operations

All write actions — equipping an avatar, toggling ranking visibility — go through the web services
already validated and secured by `local_playergames`. This block performs no writes of its own.
