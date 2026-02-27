# by40Q Workspace

Development workspace for 40Q Agency WordPress plugins. **This repo is for development only** — it is never deployed to production.

## Requirements

- Node.js >= 20
- npm >= 10
- Composer >= 2
- Docker Desktop

## Quick Start

```bash
# 1. Clone with submodules
git clone --recurse-submodules https://github.com/40q/by40q-workspace.git
cd by40q-workspace

# 2. Install all JS dependencies (all plugins via workspaces)
npm install

# 3. Install all PHP dependencies (all plugins via path repos)
composer install

# 4. Start WordPress environment
npm run env:start

# 5. Start file watchers for all plugins
npm run build:watch
```

WordPress runs at: http://localhost:8888
Admin: http://localhost:8888/wp-admin (admin / password)

## Useful Commands

| Command               | Description                    |
| --------------------- | ------------------------------ |
| `npm run env:start`   | Start WordPress (Docker)       |
| `npm run env:stop`    | Stop WordPress                 |
| `npm run env:restart` | Restart + update WordPress     |
| `npm run env:reset`   | Reset database                 |
| `npm run env:destroy` | Destroy environment completely |
| `npm run build`       | Build all plugins              |
| `npm run build:watch` | Watch + build all plugins      |
| `npm run lint`        | Lint all plugins               |

## Adding a New Plugin

```bash
# 1. Add as submodule
git submodule add https://github.com/40q/by40q-NEW-PLUGIN.git plugins/by40q-new-plugin

# 2. Add to .wp-env.json plugins array
# 3. Add to package.json workspaces array
# 4. Add to workspace composer.json repositories + require
# 5. npm install && composer install
# 6. npm run env:restart
```

## Namespace Reference

| Context                 | Namespace                                 |
| ----------------------- | ----------------------------------------- |
| PHP                     | `By40Q\Core\...` / `By40Q\PluginName\...` |
| npm packages            | `@by40q/core` / `@by40q/plugin-name`      |
| WP function prefix      | `by40q_`                                  |
| WP slug / handle prefix | `by40q-`                                  |
| WP text domain          | `by40q`                                   |
| WP option prefix        | `by40q_`                                  |
| REST API namespace      | `by40q/v1`                                |

## Repo Structure

```
by40q-workspace/
├── .wp-env.json          ← wp-env config (all plugins)
├── package.json          ← npm workspaces root
├── composer.json         ← composer path repositories (dev)
├── .gitmodules           ← submodule references
└── plugins/
    ├── by40q-core/       → github.com/40q/by40q-core
    └── by40q-*/          → github.com/40q/by40q-*
```
