<script setup>
import { onMounted, watch } from "vue"
import mapboxgl from "mapbox-gl"

const props = defineProps({
    markers: {
        type: Array,
        default: () => [],
    },
    zoom: {
        type: Number,
        default: 12,
    },
    height: {
        type: String,
        default: "500px",
    },
})

// Map instance
let map

onMounted(() => {
    const config = useRuntimeConfig()
    mapboxgl.accessToken = config.public.mapboxToken

    // Default center: first marker or Manila
    const center = props.markers.length
        ? [props.markers[0].longitude, props.markers[0].latitude]
        : [120.9842, 14.5995]

    map = new mapboxgl.Map({
        container: "map",
        style: "mapbox://styles/mapbox/satellite-streets-v11",
        //         "mapbox://styles/mapbox/streets-v11"(default )

        //          "mapbox://styles/mapbox/satellite-v9" ✅

        //         "mapbox://styles/mapbox/satellite-streets-v11"(satellite + streets overlay)

        //          "mapbox://styles/mapbox/outdoors-v11"

        //          "mapbox://styles/mapbox/dark-v10"

        //          "mapbox://styles/mapbox/light-v10"
        center,
        zoom: props.zoom,
    })

    renderMarkers(props.markers)
})

// Re-render markers when prop changes
watch(
    () => props.markers,
    (newMarkers) => {
        renderMarkers(newMarkers)
    },
    { deep: true }
)

function renderMarkers(markers) {
    if (!map) return

    // Clear old markers (optional: store refs if you want to remove them properly)
    document.querySelectorAll(".mapboxgl-marker").forEach((el) => el.remove())

    markers.forEach((marker) => {
        new mapboxgl.Marker()
            .setLngLat([parseFloat(marker.longitude), parseFloat(marker.latitude)])
            .setPopup(new mapboxgl.Popup().setText(marker.title || "Marker"))
            .addTo(map)
    })

    if (markers.length === 1) {
        map.flyTo({ center: [markers[0].longitude, markers[0].latitude], zoom: props.zoom })
    }
}


</script>

<template>
    <div :style="{ width: '100%', height }" id="map" class="rounded-lg shadow" />
</template>
