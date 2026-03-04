# 40Q Plugin Workspace Hub

Development workspace for 40Q Agency WordPress plugins. **This repo is for development only** — it is never deployed to production.

## Requirements

- Node.js >= 20
- yarn
- Composer
- Docker Desktop

## Quick Start

### Option 1: Local Development with Docker

```bash
# 1. Clone with submodules
git clone --recurse-submodules https://github.com/40q/40q-plugin-hub.git
cd 40q-plugin-hub

# 2. Install all JS dependencies (all plugins via workspaces)
yarn

# 3. Install all PHP dependencies (all plugins via path repos)
composer install

# 4. Start WordPress environment
yarn env:start

# 5. Start file watchers for all plugins
yarn build:watch
```

WordPress runs at: http://localhost:8888
Admin: http://localhost:8888/wp-admin (admin / password)

### Option 2: Online with WordPress Playground

Try our plugins instantly in WordPress Playground without setting up Docker:

[![Open in WordPress Playground](https://img.shields.io/badge/Open%20in-WordPress%20Playground-blue)](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/40Q/by40q-global-settings/master/blueprint.json)

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
