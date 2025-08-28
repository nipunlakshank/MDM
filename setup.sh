#!/usr/bin/env bash

# author: @nipunlakshank
# description: A simple setup script for PHP/Laravel projects
# usage: bash setup.sh
# version: 0.1.0

run_with_spinner() {
    local cmd="$1"
    local message="$2"
    local spin_chars=("⠋" "⠙" "⠹" "⠸" "⠼" "⠴" "⠦" "⠧" "⠇" "⠏")
    local i=0

    echo -ne "$message... "

    # Start spinner in background
    while true; do
        char="${spin_chars[i++ % ${#spin_chars[@]}]}" # Properly cycle through array
        echo -ne "\r$message... $char"
        sleep 0.1
    done &
    SPIN_PID=$!

    # Run the actual command
    eval "$cmd" &>/dev/null # Run command silently
    CMD_STATUS=$?           # Capture exit status

    # Stop the spinner
    kill $SPIN_PID
    wait $SPIN_PID 2>/dev/null

    # Print final message based on success/failure
    if [ $CMD_STATUS -eq 0 ]; then
        echo -e "\r$message... Done. ✅\n"
    else
        echo -e "\r$message... Failed ❌\n"
        return $CMD_STATUS
    fi
}

run_with_spinner "git config core.hooksPath .git-hooks" "Setting up git configs"

# Install composer packages
if [ -f composer.json ]; then
    run_with_spinner "composer install" "Installing composer packages"
fi

copy_env() {
    run_with_spinner "cp .env.example .env" "Copying .env.example to .env..."
    update_env

    if [ -f artisan ]; then
        run_with_spinner "php artisan key:generate" "Generating application key..."
    fi
}

update_env() {
    echo -e "Updating .env file...\n"
    # Get the database credentials
    read -p "Enter the database name: " DB_DATABASE
    read -p "Enter the database username: " DB_USERNAME
    read -s -p "Enter the database password: " DB_PASSWORD
    echo ""

    # Determine OS (Linux vs macOS) for `sed`
    if [[ "$OSTYPE" == "darwin"* ]]; then
        # macOS (BSD sed requires empty quotes)
        sed -i '' "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
        sed -i '' "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
        sed -i '' "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
    else
        # Linux
        sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
        sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
        sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
    fi

    echo -e "\n.env file updated. ✅"
}

# Copy .env.example to .env
if [ -f .env ]; then
    read -p "Existing .env file found. What do you want to do? (Copy & Update[c]/Update[u]/Do nothing[n]) [n]: " -n 1 -r
    echo ""

    if [[ $REPLY =~ ^[Uu]$ ]]; then
        update_env
    elif [[ $REPLY =~ ^[Cc]$ ]]; then
        copy_env
    else
        echo -e "\nSkipping .env update..."
    fi
else
    copy_env
fi

echo -e "\nCreating database (if not exists)..."

# Extract the database credentials from the .env file if not defined
DB_DATABASE=${DB_DATABASE:-$(grep DB_DATABASE .env | cut -d '=' -f2)}
DB_USERNAME=${DB_USERNAME:-$(grep DB_USERNAME .env | cut -d '=' -f2)}
DB_PASSWORD=${DB_PASSWORD:-$(grep DB_PASSWORD .env | cut -d '=' -f2)}

# Create the database
run_with_spinner 'mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\`"' 'Creating database'

# Run migrations
if [ -f artisan ]; then
    php artisan migrate
fi

# Install & Build npm assets
if [ -f package.json ]; then
    npm install
    npm run build
fi

echo "Setup completed successfully."
if [ -f artisan ]; then
    echo "Run 'php artisan serve' to start the server or 'composer run dev' to start the server and watch for changes."
fi
