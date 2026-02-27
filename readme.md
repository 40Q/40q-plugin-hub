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
yarn install

# 3. Install all PHP dependencies (all plugins via path repos)
composer install

# 4. Start WordPress environment
yarn env:start

# 5. Start file watchers for all plugins
yarn build:watch
```

WordPress runs at: http://localhost:8888
Admin: http://localhost:8888/wp-admin (admin / password)

## Adding a New Plugin

```bash
# Quick scaffold from boilerplate
./scripts/create-plugin.sh my-feature-name

# Manual setup
# 1. Add as submodule
git submodule add https://github.com/40q/by40q-NEW-PLUGIN.git plugins/by40q-new-plugin

# 2. Add to .wp-env.json plugins array
# 3. Add to package.json workspaces array
# 4. Add to workspace composer.json repositories + require
# 5. yarn install && composer install
# 6. yarn env:restart
```
