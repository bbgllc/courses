
<template>
    
   
    <vcl-twitch></vcl-twitch>
    <!--
    <vcl-instagram></vcl-instagram>
    <vcl-facebook></vcl-facebook>
    
    
    -->
    
</template>

<script>

    import { VclFacebook, VclInstagram, VclTwitch } from 'vue-content-loading';
    
    export default {
        data: function () {
            return {
               
            }
        },    
        
        components: {
            VclFacebook,
            VclInstagram,
            VclTwitch
        },
        
       
        methods: {
           
        },
        
        mounted() {
            
        }
        
    }
</script>
