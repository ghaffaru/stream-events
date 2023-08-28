<template>
    <div class="app">
        <h1>Events</h1>

        <table>
            <thead>
                <th>Event</th>
            </thead>
            <tbody>
                <tr v-for="event in events">
                    <td>{{event.name}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    name: 'example',
    data() {
        return {
            events: []
        }
    },
    mounted() {
        console.log('Mounted');
        axios.get('/api/events')
            .then(res => {
                this.events = res.data;
            })
    }
}
</script>
