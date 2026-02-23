<template>
    <div id="map" class="w-full h-[500px] rounded shadow"></div>
</template>

<script setup>
import { onMounted, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps({
  markers: {
    type: Array,
    required: true,
    default: () => []
  }
})

let map

onMounted(() => {

})

watch(
  () => props.markers,
  (newMarkers) => {
    if (newMarkers.length && !map) {
      initMap()
    } else if (map) {
      updateMarkers()
    }
  },
  { deep: true }
)

function initMap() {
  const defaultCenter = getInitialCenter()
  map = L.map('map').setView(defaultCenter, 12)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map)

  updateMarkers()
}

function getInitialCenter() {

  if (!props.markers.length) {
    console.log("no initial coordinates", props.markers)
    return [12.7, 121.6] // fallback default
  }

  const validCoords = props.markers
    .filter(m => m.latitude && m.longitude)
    .map(m => ({
      lat: parseFloat(m.latitude),
      lng: parseFloat(m.longitude)
    }))
    .filter(m => !isNaN(m.lat) && !isNaN(m.lng))

  if (!validCoords.length) {
    console.log("invalid coordinates")
    return [12.7, 121.6] // fallback
  }

  const avgLat = validCoords.reduce((sum, m) => sum + m.lat, 0) / validCoords.length
  const avgLng = validCoords.reduce((sum, m) => sum + m.lng, 0) / validCoords.length

  return [avgLat, avgLng]
}

function updateMarkers() {
  props.markers.forEach(marker => {
    if (!marker.latitude || !marker.longitude) return

    const lat = parseFloat(marker.latitude)
    const lng = parseFloat(marker.longitude)

    if (!isNaN(lat) && !isNaN(lng)) {
      const leafletMarker = L.marker([lat, lng]).addTo(map)
      if (marker.image_path) {
        leafletMarker.bindPopup(`<img src="${marker.image_path}" alt="Image" class="w-[120px]" />`)
      }
    }
  })
}

onMounted(async () => {
  await nextTick()

  if (!map && props.markers.length) {
    initMap()
  }
})
</script>

<style scoped>
:global(.leaflet-container) {
  z-index: 0;
}
</style>
