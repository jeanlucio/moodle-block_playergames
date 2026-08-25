# ✨ Features

* 🦊 **Avatar at a glance:** shows the equipped avatar; clicking it opens the same avatar-collection
  modal used by the Player Hub, so equipping there or here stays in sync.
* 📊 **Season progress:** level, XP and a progress bar toward the next level.
* 🎮 **Today's games:** shortcuts to the day's available mini-games, with done/pending/locked state.
* 🔥 **Streak, freezes & check-ins:** compact stats with accessible labels.
* 📘 **Learning XP (optional):** shows the XP mirrored from course activity via `block_playerhud`,
  when that bridge and the admin setting are both active — student-only, never mixed with season XP.
* 🏆 **Ranking position + one-click opt-in:** season and learning ranking positions, each with its
  own toggle to appear or not — no need to open the full Hub.
* ❓ **Built-in help:** a short modal explaining the two XP pools, avatars and rankings — the same
  content shown on every Player Hub page.
* 🕐 **Activity history shortcut:** a direct link to the full log of XP, streak and freeze events.
* 🔌 **Three states, no logic of its own:**
  * *Normal* — active player: avatar, level, XP bar, today's games, streak and rankings all
    visible.
  * *Paused* — the user opted out of gamification, with a link to reactivate directly from the
    block.
  * *Hidden* — no active season, or the viewer is excluded by the site's participant-group
    setting.

  All three states are computed by `local_playergames`; the block only picks which template to
  render.
