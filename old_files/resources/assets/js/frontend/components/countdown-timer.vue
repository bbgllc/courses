
<template>
    <div>
        <Countdown :deadline="deadline"></Countdown>
    </div>
</template>


<script>

    import Countdown from 'vuejs-countdown'
    
    export default {
        props: ['deadline'],
        
        components: { Countdown },
        
        mounted() {

        }
    }
</script>

<style>
    .vue-countdown .digit {
        font-size: 15px !important;
    }
    .vue-countdown li {
        margin: 0 5px !important;
    }
    .vue-countdown li:after {
        font-size: 15px !important;
        font-weight: bold;
        right: -8px;
    }
</style>
