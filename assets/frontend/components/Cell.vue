<template>
    <div class="cell">
        <h3>
            Клетка ({{ label }})
            <button class="remove-btn" @click="$emit('remove')">x</button>
        </h3>

        <div class="animals">
            <Animal
                v-for="(animal, i) in cell.animals"
                :key="i"
                :animal="animal"
                @remove="cell.animals.splice(i, 1)"
            />
        </div>

        <div class="controls">
            <button
                v-for="a in availableAnimals"
                :key="a"
                @click="addAnimal(a)"
            >Добавить {{ a }}</button>

            <button @click="clean">🧹 Почистить клетку</button>
        </div>
    </div>
</template>

<script>
import Animal from './Animal.vue'

export default {
    components: { Animal },
    props: {
        cell: Object
    },
    computed: {
        label() {
            return this.cell.type === 'predator' ? 'Хищники' : 'Травоядные'
        },
        availableAnimals() {
            return this.cell.type === 'predator'
                ? ['Лев', 'Крокодил']
                : ['Слон']
        }
    },
    methods: {
        addAnimal(type) {
            this.cell.animals.push({
                type,
                actions: this.getActions(type)
            })
        },
        getActions(type) {
            switch (type) {
                case 'Лев': return ['Рычать', 'Питаться']
                case 'Слон': return ['Поливать себя хоботом', 'Питаться']
                case 'Крокодил': return ['Рычать', 'Плавать', 'Питаться']
            }
        },
        clean() {
            if (this.cell.animals.length > 0) {
                alert('Нельзя чистить клетку, пока в ней есть животные!')
            } else {
                alert('Клетка почищена')
            }
        }
    }
}
</script>

<style scoped>
.cell {
    border: 2px solid #ccc;
    border-radius: 10px;
    padding: 1rem;
    width: 260px;
    background: #f9f9f9;
}
.animals {
    margin: 1rem 0;
}
.controls button {
    margin: 0.3rem;
}
.remove-btn {
    float: right;
    background: none;
    border: none;
    color: #900;
    font-weight: bold;
    cursor: pointer;
}
</style>

