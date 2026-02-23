module.exports = {
  apps: [
    {
      name: "projectA-laravel",
      cwd: "C:/xampp/htdocs/ppdo-ipmis/ipmis",
      script: "php",
      args: "artisan serve --host=192.168.6.81 --port=8000",
      watch: false
    },
    {
      name: "projectA-nuxt",
      cwd: "C:/xampp/htdocs/ppdo-ipmis/ipmis-app",
      script: "npm",
      args: "run dev -- --host",
      watch: false
    }
  ]
};
