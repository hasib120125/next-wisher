import { ref } from 'vue'

const useVideo = () => {
    const videoPlayer = ref(null)
    const isPlaying = ref(true)
    const isMuted = ref(false)
    const volume = ref(1)

    function togglePlay(videoPlayer) {
        isPlaying.value = !isPlaying.value

        if(isPlaying.value){
            videoPlayer.pause()
            return
        }
        videoPlayer.play()
    }

    function toggleVolume(){
        isMuted.value = !isMuted.value
    }

    return {
        videoPlayer,
        isPlaying,
        volume,
        togglePlay,
        toggleVolume,
        isMuted
    }
}

export default useVideo