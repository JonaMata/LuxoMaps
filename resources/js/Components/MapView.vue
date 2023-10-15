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
    Point, Icon
} from 'leaflet'
import {onMounted, Ref, ref, watch, watchEffect} from "vue";
import {router, usePage} from "@inertiajs/vue3";
// @ts-ignore
import {GeoSearchControl, OpenStreetMapProvider} from "leaflet-geosearch";
import "leaflet"
import "leaflet.markercluster"

const page = usePage()

const props = defineProps<{
    stickers: Array<any>,
    canEdit?: boolean,
}>()

const trackLocation = ref(false)

let map : LeafletMap;

let locationMarker : Marker, accuracyCircle : Circle, curLocGroup : FeatureGroup
let currentLocation = ref()

const markers : any = L.markerClusterGroup({
    animateAddingMarkers: true,
    iconCreateFunction: (cluster : any) => {
        const childCount = cluster.getChildCount()
        return new DivIcon({
            html: '<div><span>' + childCount + '</span></div>',
            className: 'cluster-icon',
            iconSize: new Point(32, 32, true),
        })
    }
})

let shouldPan = false;

//Define LuxoIcon
const LuxoIcon = new Icon({
    iconUrl: '/luxomarker.png',
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

    markers.addTo(map)

    //Default marker
    const moodMarker = new Marker([52.22224319001547, 6.895161306827223]).setIcon(LuxoIcon).addTo(map)

    moodMarker.bindPopup('Mood')

    const newMarkers : Marker[] = []
    props.stickers.forEach((sticker) => newMarkers.push(placeSticker(sticker)))
    markers.addLayers(newMarkers)

    //Map click
    if (props.canEdit) {
        const newStickerPopup = new Popup({
            content:
                '<button ' +
                'id="stickerButton" ' +
                'class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"' +
                '>' +
                'Sticker plakken' +
                '</button>'
        })
        map.on('click', (e) => {
            newStickerPopup
                .setLatLng(e.latlng)
                .openOn(map)
            const stickerButton = document.getElementById('stickerButton')
            if (stickerButton) stickerButton.onclick = () => {
                newStickerPopup.close()
                postSticker(e.latlng)
            }
        })
    }

    locationMarker = new Marker([0,0])
    accuracyCircle = new Circle([0,0])
    curLocGroup = new FeatureGroup([locationMarker, accuracyCircle])


    watch(() => props.stickers, (stickers, prevStickers) => {
        markers.clearLayers()
        const newMarkers : Marker[] = []
        let newestMarker : Marker | undefined

        stickers.forEach((sticker) => newMarkers.push(placeSticker(sticker)))
        if (stickers.length > prevStickers.length) {
            newestMarker = newMarkers.pop()
        }
        markers.addLayers(newMarkers)
        if (newestMarker) {
            markers.addLayer(newestMarker).openPopup()
        }
    })

    const search = new GeoSearchControl({
        provider: new OpenStreetMapProvider(),
        style: 'bar',
        notFoundMessage: 'Geen resultaten gevonden',
    });

    map.addControl(search);
})


function postSticker(latLng : any) {
    router.post(route('stickers.create'), latLng, {
        onError: (errors) => console.log(errors),
    });
}

function placeSticker(sticker : any) {
    const marker = new Marker([sticker.latitude, sticker.longitude]).setIcon(LuxoIcon)
    let popupContent = 'Geplakt door ' + sticker.owner;
    if (props.canEdit && sticker.is_owner) {
        const ownerPopup = new Popup().setLatLng([sticker.latitude, sticker.longitude]).setContent('Geplakt door jou!<br><br><button id="sticker-' + sticker.id + '" ' +
            'class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" ' +
            '>Verwijderen</button>')

        marker.on('click', () => {
            ownerPopup.openOn(map)
            document.getElementById('sticker-' + sticker.id)?.addEventListener('click', () => {
                ownerPopup.remove()
                router.delete(route('stickers.delete', {
                    sticker: sticker.id,
                }), {
                    onError: (errors) => console.log(errors)
                })
            })
        })
    } else {
        marker.bindPopup(popupContent)
    }
    return marker
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
    </div>
</template>

<style scoped>
#map {
    width: 100%;
    height: 100%;
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
</style>
