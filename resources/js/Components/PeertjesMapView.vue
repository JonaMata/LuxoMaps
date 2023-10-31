<script setup lang="ts">
import 'leaflet/dist/leaflet.css'
import 'leaflet-geosearch/dist/geosearch.css'
import 'leaflet.markercluster/dist/MarkerCluster.css'
import 'leaflet.markercluster/dist/MarkerCluster.Default.css'
import L, {
    Marker,
    Map as LeafletMap,
    Circle,
    FeatureGroup,
    LatLngTuple,
    LatLng,
    TileLayer,
    Popup,
    DivIcon,
    Point, Icon, CircleMarker, Polyline
} from 'leaflet'
import {onMounted, Ref, ref, watch, watchEffect} from "vue";
import {router, usePage} from "@inertiajs/vue3";
// @ts-ignore
import {GeoSearchControl, OpenStreetMapProvider} from "leaflet-geosearch";
import "leaflet"
import "leaflet.markercluster"
import moment from "moment";

interface Location {
    latitude: number,
    longitude: number,
}

moment().locale('nl')

const page = usePage()

const props = defineProps<{
    peertjes: Array<any>,
}>()

const trackLocation = ref(false)


const peertjeMarkers: Array<Ref<Array<Marker | CircleMarker>>> = props.peertjes.map((peertje) => ref([]));
const peertjeLines: Array<Ref<Polyline | undefined>> = props.peertjes.map((peertje) => ref());

let map : LeafletMap;

let locationMarker : Marker, accuracyCircle : Circle, curLocGroup : FeatureGroup
let currentLocation = ref()

let shouldPan = false;

const LuxoIcon = new DivIcon({
    className: 'peertje-icon pulsing',
    html: '💡',
    iconSize: [32, 32],
    iconAnchor: [16, 16],
    popupAnchor: [0, 0],
})

onMounted(() => {
    map = new LeafletMap('map').setView([52.22224319001547, 6.895161306827223], 13)
    new TileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map)

    props.peertjes.forEach((peertje, peertjeIndex) => {
        let lineLocs : Array<LatLng> = []
        peertje.locations.forEach((location: Location) => lineLocs.push(new LatLng(location.latitude, location.longitude)))
        peertjeLines[peertjeIndex].value = L.polyline(lineLocs, {color: 'blue'}).addTo(map)
        peertje.locations.forEach((location: Location, locationIndex: number) => peertjeMarkers[peertjeIndex].value.push(placeMarker(peertje, location, 0==locationIndex)))
    })


    locationMarker = new Marker([0,0])
    accuracyCircle = new Circle([0,0])
    curLocGroup = new FeatureGroup([locationMarker, accuracyCircle])


    watch(() => props.peertjes, (peertjes, prevPeertjes) => {
        peertjes.forEach((peertje, peertjeIndex) => {
            if (peertje.locations.length == prevPeertjes[peertjeIndex].locations.length) return;
            peertjeMarkers[peertjeIndex].value.forEach((marker) => {
                marker.remove()
            })
            peertjeLines[peertjeIndex].value?.remove();

            peertjeMarkers[peertjeIndex].value = []

            peertje.locations.forEach((location: Location, locationIndex: number) => {
                peertjeMarkers[peertjeIndex].value.push(placeMarker(peertje, location, 0==locationIndex))
            })
            const lineLocs : Array<LatLng> = peertje.locations.map((loc: Location) => new LatLng(loc.latitude, loc.longitude))
            peertjeLines[peertjeIndex].value = L.polyline(lineLocs, {color: 'blue'}).addTo(map) as Polyline


            if (peertje.locations.length > prevPeertjes[peertjeIndex].locations.length) {
                peertjeMarkers[peertjeIndex].value[0].openPopup()
            }
        })
    })

    setInterval(() => {
        router.reload({
          only: ['peertjes'],
        })
    }, 2000);
})

function placeMarker(peertje: any, location : any, newest: boolean = false) {
    let marker: Marker | CircleMarker;
    marker = new CircleMarker([location.latitude, location.longitude], {radius: 4})
    if (newest) {
        marker = new Marker([location.latitude, location.longitude])
        marker.setIcon(LuxoIcon)
    }
    const time = moment(location.created_at)
    let popupContent = 'Peertje ' + peertje.name + '<br>om ' + time.format('HH:mm:ss') + '<br>' + time.fromNow() + '<br>🔋' + location.battery_percentage + '%';
    marker.bindPopup(popupContent)

    return marker.addTo(map)
}

function updateCurrentPosition(location : GeolocationPosition) {
    const latLng : LatLngTuple = [
        location.coords.latitude,
        location.coords.longitude,
    ]

    currentLocation.value = latLng

    locationMarker.setLatLng(latLng)
    accuracyCircle.setLatLng(latLng).setRadius(location.coords.accuracy)
    curLocGroup.addTo(map)

    if (shouldPan) {
        map.flyToBounds(accuracyCircle.getBounds())
        shouldPan = false
    }


    if (!trackLocation.value) return
    setTimeout(() => navigator.geolocation.getCurrentPosition(updateCurrentPosition), 500)
}

async function toggleLocation() {
    if (!trackLocation.value) {
        const result = await navigator.permissions.query({name: 'geolocation'})
        if (result.state === 'granted') {
            trackLocation.value = true
            shouldPan = true
            navigator.geolocation.getCurrentPosition(updateCurrentPosition)
        } else if (result.state === 'prompt') {
            navigator.geolocation.getCurrentPosition((position) => {
                trackLocation.value = true
                shouldPan = true
                updateCurrentPosition(position)
            })
        }
        result.addEventListener('change', () => {
            trackLocation.value = result.state === 'granted';
        })
    } else {
        trackLocation.value = false
        curLocGroup.removeFrom(map)
    }
}
</script>

<template>
    <div class="relative grow flex flex-col">
        <div id="map" class="grow"></div>
        <button
            class="text-xl border-black/30 border-2 z-[999] absolute right-0 top-0 mt-2 mr-2 px-2 aspect-square rounded transition ease-in-out duration-150"
            :class="trackLocation ? 'bg-blue-500 hover:bg-blue-700 text-white' : 'bg-white hover:bg-gray-200'"
            @click="toggleLocation">&target;
        </button>

        <div class="text-xl border-black/30 border-2 z-[999] absolute left-0 bottom-0 m-2 px-2 rounded bg-white">
            <ul>
                <li v-for="peertje in props.peertjes" :key="peertje.id">
                    {{ peertje.locations[0].battery_percentage < 20 ? '🪫' : '🔋'}}{{ peertje.locations[0].battery_percentage ?? 'unknown' }}% {{ peertje.name }}
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
#map {
    width: 100%;
    height: -webkit-fill-available;
}
</style>

<style>
.cluster-icon {
    background: linear-gradient(0, rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('/luxomarker.png');
    border-radius: 50%;
    background-size: contain;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.5em;
}

.peertje-icon {
    font-size: 32px;
    background: rgba(0,0,0,0.5);
    border-radius: 50%;
}

.pulsing {
    animation: pulse 2s linear infinite;
}

@keyframes pulse {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        opacity: 1;
    }
}
</style>
