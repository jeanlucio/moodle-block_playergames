# 🕹️ PlayerGames Ecosystem

The PlayerGames Block is a companion to **`local_playergames`**, the ecosystem's central hub. It is
designed to complement — not overlap with — **`block_playerhud`**:

| | Scope | Where it appears |
|---|---|---|
| **`block_playerhud`** | Per-course XP, items, quests, story | Inside a course |
| **`block_playergames`** (this plugin) | Site-wide season XP, streak, rankings | Site front page and Dashboard, **not** inside courses |

* **PlayerGames Hub (`local_playergames`):** the required dependency — hub page, rankings,
  missions, avatar catalog, activity log.
  👉 [github.com/jeanlucio/moodle-local_playergames](https://github.com/jeanlucio/moodle-local_playergames)

* **PlayerHUD Block (`block_playerhud`):** per-course gamification block. Its course XP can
  optionally mirror into this ecosystem's "Learning XP" pool.
  👉 [github.com/jeanlucio/moodle-block_playerhud](https://github.com/jeanlucio/moodle-block_playerhud)

* **AI Hub (`local_aihub`):** shared BYOK broker for AI-assisted cartridge generation in
  `local_playergames`. Optional.
  👉 [github.com/jeanlucio/moodle-local_aihub](https://github.com/jeanlucio/moodle-local_aihub)
