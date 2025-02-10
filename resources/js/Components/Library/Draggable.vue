<template>
    <div 
        @mousedown="handleMouseDown"
        ref="__parent_elem"
    >
        <slot></slot>
    </div>
  </template>
  
  <script setup>
  import { onMounted, onUnmounted, ref } from 'vue'

  const props = defineProps({
    init: {
      type: Object,
      default: {
        top: 0,
        left: 0
      }
    },
    moveable: {
      type: Boolean,
      default: true
    }
  })

  const __parent_elem = ref()
  
  // handle draggable---------
  const mousePrevPos = {x: 0, y: 0}
  const elementPrevPos = {x: props.top, y: props.left}
  const emit = defineEmits()
  let isMoveable     = false
  let moveableElement = null
  let calculatedPosition = {left: props.left, top: props.top}


  const handleMouseDown = (e) => {
    if(!props.moveable) return

    isMoveable = true
    mousePrevPos.x = e.x
    mousePrevPos.y = e.y

    let top  = moveableElement.offsetTop
    let left = moveableElement.offsetLeft
    elementPrevPos.top  = top
    elementPrevPos.left = left
  }
  
  const handleMouseUp = () => {
    if(!isMoveable) return
    emit('dragend', calculatedPosition, moveableElement)
    isMoveable = false
  }
  
  const handleMouseMove = (e) => {
    if(isMoveable && moveableElement){
        let distanceX = (e.x - mousePrevPos.x)
        let distanceY = (e.y - mousePrevPos.y)
        
        calculatedPosition.left = (elementPrevPos.left + distanceX)
        calculatedPosition.top = (elementPrevPos.top + distanceY)

        moveableElement.style.left = calculatedPosition.left + 'px'
        moveableElement.style.top = calculatedPosition.top + 'px'

        emit('dragging', calculatedPosition, moveableElement)
    }
  }

  onMounted(() => {
    moveableElement = __parent_elem.value.firstElementChild
    moveableElement.style.left = props.init.left + 'px'
    moveableElement.style.top  = props.init.top + 'px'


    addEventListener('mousemove', handleMouseMove)
    addEventListener('mouseup', handleMouseUp)
  })

  onUnmounted(() => {
    removeEventListener('mousemove', handleMouseMove)
    removeEventListener('mouseup', handleMouseUp)
  })
  </script>