<template>
    <div class="app">
        <h1>Events</h1>
        <div style="border: 2px solid black; width: 250px; float: left">
            <h3>Total Followers (last 30 days)</h3>
            <h1>{{ total_followers }}</h1>
        </div>
        <div style="border: 2px solid black; width: 250px; float: left">
            <h3>Total Revenue (last 30 days)</h3>
            <h1>{{ total_revenue }}  USD</h1>
        </div>

        <div style="border: 2px solid black; width: 250px; float: left">
            <h3>Top3 Merch (last 30 days)</h3>

            <span v-for="top3 in top3_merch" >
                {{ top3.item_name }},
            </span>
            <br /><br />
        </div>
        <br>

        <table style="clear: both">
            <h1 v-if="error">{{error}}</h1>
            <tbody v-if="events">
                <tr v-for="event in events">
                    <td>{{event.name}}</td>
                    <br /><br />
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
            events: [],
            total_followers: '',
            total_revenue: '',
            top3_merch: [],
            error: ''
        }
    },
    mounted() {

        axios.get('/api/events')
            .then(res => {
                this.events = res.data;
            })
        .catch(err => {
            this.error = err.response.message;
        })

        axios.get('/api/statistics')
            .then(res => {
                this.total_followers = res.data.total_followers
                this.total_revenue = res.data.total_revenue
                this.top3_merch = res.data.top3_merch
            })
    }
}
</script>
