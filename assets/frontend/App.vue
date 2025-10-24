<script setup>
import { ref, onMounted } from 'vue'

const cells = ref([])
const speciesOptions = [
    { label: 'льва', name: 'lion', type: 'predator' },
    { label: 'крокодила', name: 'crocodile', type: 'predator' },
    { label: 'слона', name: 'elephant', type: 'herbivore' },
]
const speciesLabels = {
    lion: 'Лев',
    elephant: 'Слон',
    crocodile: 'Крокодил',
}
const cellsLabels = {
    herbivore: 'для травоядных',
    predator: 'для хищников',
}

async function loadZoo() {
    const res = await fetch('/api/zoo/cells')
    cells.value = await res.json()
}

async function addCell(type) {
    const res = await fetch('/api/zoo/cell', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ type }),
    })
    const data = await res.json()
    cells.value.push(data)
}

async function addAnimal(eid, s) {
    const res = await fetch('/api/zoo/animal', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cellId: eid, species: s.name, type: s.type }),
    })
    const data = await res.json()
    if (data.error) {
        alert(data.error)
        return
    }
    const enc = cells.value.find(e => e.id === eid || e.id === Number(eid))
    if (!enc) {
        await loadZoo()
        return
    }
    if (!Array.isArray(enc.animals)) {
        enc.animals = []
    }
    enc.animals.push(data)
}



async function feedAnimal(id) {
    const res = await fetch(`/api/zoo/animal/feed/${id}`, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' },
    })
    const data = await res.json()
    if (data.error) {
        alert(data.error)
    } else if (data.message) {
        alert(data.message)
    }
}


async function cleanCell(id) {
    const res = await fetch(`/api/zoo/cell/clean/${id}`, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' },
    })
    const data = await res.json()
    if (data.error) {
        alert(data.error)
    } else if (data.message) {
        alert(data.message)
    }
}


async function performAction(id, action) {
    const res = await fetch('/api/zoo/animal/action', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, action: action } )
    })
    const data = await res.json()
    if (data.error) {
        alert(data.error)
    } else if (data.message) {
        alert(data.message)
    }
}

async function removeAnimal(id, eid) {
    await fetch(`/api/zoo/animal/${id}`, { method: 'DELETE' })
    const enc = cells.value.find(e => e.id === eid)
    enc.animals = enc.animals.filter(a => a.id !== id)
}


onMounted(loadZoo)
</script>

<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Виртуальный зоопарк</h1>

        <div class="space-x-2 mb-4">
            <button
                class="bg-green-500 text-white px-3 py-1 rounded"
                @click="addCell('herbivore')"
            >
                Добавить клетку для травоядных
            </button>
            <button
                class="bg-red-500 text-white px-3 py-1 rounded"
                @click="addCell('predator')"
            >
                Добавить клетку для хищников
            </button>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div
                v-for="enc in cells"
                :key="enc.id"
                class="border p-4 rounded-lg shadow"
            >
                <h2 class="font-bold mb-2">
                    Клетка #{{ enc.id }} ({{ cellsLabels[enc.type] }})
                </h2>

                <div class="space-x-2 mb-3">
                    <button
                        v-for="s in speciesOptions"
                        :key="s.name"
                        @click="addAnimal(enc.id, s)"
                        class="border rounded px-2 py-1 hover:bg-gray-100"
                    >
                        Добавить {{ s.label }}
                    </button>

                    <button
                        @click="cleanCell(enc.id)"
                        class="border rounded px-2 py-1 hover:bg-gray-100"
                    >
                        Чистить
                    </button>

                </div>

                <ul>
                    <li
                        v-for="a in enc.animals"
                        :key="a.id"
                        class="flex justify-between border-b py-1"
                    >
                        {{ speciesLabels[a.species] }}
                        <button
                            @click="removeAnimal(a.id, enc.id)"
                            class="text-red-500"
                        >
                            ×
                        </button>
                        <button
                            @click="feedAnimal(a.id)"
                            class="border rounded px-2 py-1 hover:bg-gray-100"
                        >
                            Питаться
                        </button>

                        <button
                            @click="performAction(a.id, 'swim')"
                            class="border rounded px-2 py-1 hover:bg-gray-100"
                        >
                            Плавать
                        </button>

                        <button
                            @click="performAction(a.id, 'roar')"
                            class="border rounded px-2 py-1 hover:bg-gray-100"
                        >
                              Рычать
                        </button>

                        <button
                            @click="performAction(a.id, 'water')"
                            class="border rounded px-2 py-1 hover:bg-gray-100"
                        >
                            Поливаться
                        </button>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped>
.p-4 {
    font-family: system-ui, sans-serif;
}
button {
    cursor: pointer;
}
</style>


