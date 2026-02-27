#!/bin/bash

# Create a new plugin from the boilerplate template
# Usage: ./scripts/create-plugin.sh plugin-name

if [ -z "$1" ]; then
    echo "Usage: ./scripts/create-plugin.sh <plugin-name>"
    echo "Example: ./scripts/create-plugin.sh my-feature"
    exit 1
fi

PLUGIN_NAME_SLUG="$1"
PLUGIN_NAME_PASCAL=$(echo "$PLUGIN_NAME_SLUG" | sed -r 's/(^|-)([a-z])/\U\2/g')
PLUGIN_DIR="$(dirname "$0")/../plugins/by40q-${PLUGIN_NAME_SLUG}"

if [ -d "$PLUGIN_DIR" ]; then
    echo "❌ Plugin directory already exists: $PLUGIN_DIR"
    exit 1
fi

echo "📋 Creating new plugin: by40q-${PLUGIN_NAME_SLUG}"

# Copy boilerplate
cp -r "$(dirname "$0")/../plugins/by40q-plugin-boilerplate" "$PLUGIN_DIR"

# Rename main plugin file
MAIN_FILE="$PLUGIN_DIR/by40q-${PLUGIN_NAME_SLUG}.php"
mv "$PLUGIN_DIR/by40q-plugin-boilerplate.php" "$MAIN_FILE"

# Update namespace and class names in all PHP files
find "$PLUGIN_DIR" -name "*.php" -exec sed -i '' \
    -e "s/By40Q\\\\Boilerplate/By40Q\\\\${PLUGIN_NAME_PASCAL}/g" \
    -e "s/Boilerplate_Activator/${PLUGIN_NAME_PASCAL}_Activator/g" \
    -e "s/Boilerplate_Deactivator/${PLUGIN_NAME_PASCAL}_Deactivator/g" \
    -e "s/class Boilerplate {/class ${PLUGIN_NAME_PASCAL} {/" \
    -e "s/Boilerplate::/instance()/By40Q\\\\${PLUGIN_NAME_PASCAL}\\\\${PLUGIN_NAME_PASCAL}::instance()/g" \
    {} +

# Update plugin header in main file
sed -i '' \
    -e "s/Plugin Name:       40Q Boilerplate Plugin/Plugin Name:       40Q ${PLUGIN_NAME_PASCAL} Plugin/" \
    -e "s/Description:       Boilerplate plugin for 40Q plugins/Description:       ${PLUGIN_NAME_PASCAL} plugin for 40Q plugins/" \
    -e "s/@package By40Q\\\\Boilerplate/@package By40Q\\\\${PLUGIN_NAME_PASCAL}/" \
    -e "s/BY40Q_BOILERPLATE_/BY40Q_${PLUGIN_NAME_SLUG^^}_/g" \
    "$MAIN_FILE"

echo "✅ Plugin created: $PLUGIN_DIR"
echo ""
echo "📝 Next steps:"
echo "   1. Update README in the plugin directory"
echo "   2. Add to .wp-env.json plugins array: \"./plugins/by40q-${PLUGIN_NAME_SLUG}\""
echo "   3. Add to package.json workspaces array: \"plugins/by40q-${PLUGIN_NAME_SLUG}\""
echo "   4. Create remote Git repo at: github.com/40Q/by40q-${PLUGIN_NAME_SLUG}.git"
echo "   5. cd $PLUGIN_DIR && git init && git remote add origin ..."
echo "   6. cd - && git submodule add git@github.com:40Q/by40q-${PLUGIN_NAME_SLUG}.git plugins/by40q-${PLUGIN_NAME_SLUG}"
echo "   7. yarn install && composer install && yarn env:restart"
