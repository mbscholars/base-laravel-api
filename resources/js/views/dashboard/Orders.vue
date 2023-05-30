<template>
    Welcome to the home page
    </template>
    <script>

    export default {
        name: 'Home',
        meta: {
            permission: 'client.read',
            redirectIfLoggedIn: false,
        }
    }
    </script>
