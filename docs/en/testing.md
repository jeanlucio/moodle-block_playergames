# 🧪 Automated Tests

The only business logic that belongs to this plugin — which of the three states to render — is
covered by PHPUnit. Everything else is orchestration of `local_playergames` managers, already
tested in that plugin.

## PHPUnit — Unit & Integration Tests

| Test file | Cases | What is covered |
|-----------|------:|----------------|
| `output/widget_test.php` | 9 | State selection: paused when gamification is opted out, hidden with no active season or when excluded by the participant-group setting, normal otherwise; normal-state data sanity (username, level, XP); the normal state's link to pause gamification; learning XP hidden when the admin setting is off; self and learning ranking position + opt-in toggle text once the user opts into each ranking |
| `privacy/provider_test.php` | 2 | Implements `null_provider`; `get_reason()` points to a real lang string |
| **Total** | **11** | |

**Line coverage by class (PHPUnit + Xdebug):**

| Class | Line coverage |
|-------|:-------------:|
| `output\widget` | 100% |
| `privacy\provider` | 100% |
| **Overall** | **100%** |

```bash
vendor/bin/phpunit --testsuite block_playergames
```

## Behat — Acceptance Tests

| Feature file | Scenarios | What is covered |
|--------------|----------:|----------------|
| `block_playergames_widget.feature` | 3 | Normal-state pause link opens the gamification preferences page; opting out from the widget shows the paused state; reactivating from the paused state restores the normal widget |
| **Total** | **3** | |

```bash
php admin/tool/behat/cli/init.php
vendor/bin/behat --tags=@block_playergames --profile=chrome
```
