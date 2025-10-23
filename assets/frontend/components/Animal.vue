<template>
    <div class="animal">
        <strong>{{ animal.type }}</strong>
        <button @click="$emit('remove')">Удалить</button>
        <ul>
            <li v-for="(a, i) in animal.actions" :key="i">
                <button @click="doAction(a)">{{ a }}</button>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        animal: Object
    },
    methods: {
        doAction(action) {
            alert(`${this.animal.type} выполняет действие: ${action}`)
        }
    }
}
</script>

<style scoped>
.animal {
    border: 1px solid #bbb;
    background: #fff;
    margin-bottom: 0.5rem;
    padding: 0.5rem;
    border-radius: 8px;
}
ul {
    list-style: none;
    padding: 0;
    margin: 0.3rem 0;
}
button {
    margin: 0.2rem;
    font-size: 0.9rem;
}
</style>

