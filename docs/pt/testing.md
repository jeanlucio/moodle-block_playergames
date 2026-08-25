# 🧪 Testes Automatizados

A única lógica de negócio deste plugin — qual dos três estados renderizar — é coberta por PHPUnit.
Todo o resto é orquestração de managers do `local_playergames`, já testados naquele plugin.

## PHPUnit — Testes Unitários e de Integração

| Arquivo de teste | Casos | O que é coberto |
|------------------|------:|----------------|
| `output/widget_test.php` | 9 | Seleção de estado: pausado quando a gamificação está desativada, oculto sem temporada ativa ou quando excluído pela configuração de grupo de participantes, normal nos demais casos; sanidade dos dados do estado normal (nome, nível, XP); link do estado normal para pausar a gamificação; XP de aprendizado oculto quando a configuração do admin está desligada; posição e texto do toggle de opt-in nos rankings de temporada e de aprendizado quando o usuário adere a cada um |
| `privacy/provider_test.php` | 2 | Implementa `null_provider`; `get_reason()` aponta para uma string de idioma real |
| **Total** | **11** | |

**Cobertura de linhas por classe (PHPUnit + Xdebug):**

| Classe | Cobertura de linhas |
|--------|:-------------------:|
| `output\widget` | 100% |
| `privacy\provider` | 100% |
| **Total** | **100%** |

```bash
vendor/bin/phpunit --testsuite block_playergames
```

## Behat — Testes de Aceitação

| Arquivo de feature | Cenários | O que é coberto |
|--------------------|--------:|----------------|
| `block_playergames_widget.feature` | 3 | Link de pausa no estado normal abre a página de preferências de gamificação; desativar pelo widget mostra o estado pausado; reativar a partir do estado pausado restaura o widget normal |
| **Total** | **3** | |

```bash
php admin/tool/behat/cli/init.php
vendor/bin/behat --tags=@block_playergames --profile=chrome
```
