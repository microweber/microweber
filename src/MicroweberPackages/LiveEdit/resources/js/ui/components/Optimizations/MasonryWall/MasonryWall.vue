<script setup lang="ts">
import type { Ref } from 'vue'
import { nextTick, onBeforeUnmount, onMounted, ref, toRefs, watch } from 'vue'

type Column = number[]

const props = withDefaults(
    defineProps<{
        columnWidth?: number
        items: unknown[]
        gap?: number
        rtl?: boolean
        ssrColumns?: number
        scrollContainer?: HTMLElement | null
    }>(),
    {
        columnWidth: 400,
        gap: 0,
        rtl: false,
        ssrColumns: 0,
        scrollContainer: null,
    }
)

const emit = defineEmits<{
    (event: 'redraw'): void
    (event: 'redrawSkip'): void
}>()

const { columnWidth, items, gap, rtl, ssrColumns, scrollContainer } =
    toRefs(props)
const columns = ref<Column[]>([])
const wall = ref<HTMLDivElement>() as Ref<HTMLDivElement>

function columnCount(): number {
    const count = Math.floor(
        (wall.value.getBoundingClientRect().width + gap.value) /
        (columnWidth.value + gap.value)
    )
    return count > 0 ? count : 1
}

function createColumns(count: number): Column[] {
    return [...new Array(count)].map(() => [])
}

if (ssrColumns.value > 0) {
    const newColumns = createColumns(ssrColumns.value)
    items.value.forEach((_: unknown, i: number) =>
        newColumns[i % ssrColumns.value]!.push(i)
    )
    columns.value = newColumns
}

async function fillColumns(itemIndex: number) {
    if (itemIndex >= items.value.length) {
        return
    }
    await nextTick()
    const columnDivs = [...wall.value.children] as HTMLDivElement[]
    if (rtl.value) {
        columnDivs.reverse()
    }
    const target = columnDivs.reduce((prev, curr) =>
        curr.getBoundingClientRect().height < prev.getBoundingClientRect().height
            ? curr
            : prev
    )
    columns.value[+target.dataset.index!]!.push(itemIndex)
    await fillColumns(itemIndex + 1)
}

async function redraw(force = false) {
    if (columns.value.length === columnCount() && !force) {
        emit('redrawSkip')
        return
    }

    columns.value = createColumns(columnCount())
    const scrollTarget = scrollContainer?.value
    const scrollY = scrollTarget ? scrollTarget.scrollTop : window.scrollY
    await fillColumns(0)
    scrollTarget
        ? scrollTarget.scrollBy({ top: scrollY - scrollTarget.scrollTop })
        : window.scrollTo({ top: scrollY })
    emit('redraw');

}

const resizeObserver =
    typeof ResizeObserver === 'undefined'
        ? undefined
        : new ResizeObserver(() => redraw())

onMounted(() => {
    redraw();
    resizeObserver?.observe(wall.value);
})

onBeforeUnmount(() => resizeObserver?.unobserve(wall.value))

watch([items, rtl], () => redraw(true))
watch([columnWidth, gap], () => redraw())
</script>

<template>

    <div
        ref="wall"
        class="masonry-wall"
        :style="{ display: 'flex', gap: `${gap}px` }"
    >
        <div
            v-for="(column, columnIndex) in columns"
            :key="columnIndex"
            class="masonry-column"
            :data-index="columnIndex"
            :style="{
        display: 'flex',
        'flex-basis': '0px',
        'flex-direction': 'column',
        'flex-grow': 1,
        gap: `${gap}px`,
        height: ['-webkit-max-content', '-moz-max-content', 'max-content'] as any,
        'min-width': 0
      }"
        >
            <div v-for="itemIndex in column" :key="itemIndex" class="masonry-item">
                <slot :item="items[itemIndex]" :index="itemIndex">
                    {{ items[itemIndex] }}
                </slot>
            </div>
        </div>
    </div>
</template>
