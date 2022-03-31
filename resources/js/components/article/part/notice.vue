<template>
	<div class="notice">
        <slot>削除完了しました。</slot>
    </div>
</template>

<script>
	export default {
        
	}
</script>

<style>
	.notice {
        position: absolute;
        left:0px;
        bottom: 0px;

        z-index: 99;
        opacity: 1;

        margin: 0 auto;
        padding: 15px;
        text-align: center;
        font-size: 124%;

        box-sizing: border-box;

        width: 100%;

        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
        border-top: 1px solid;

        
    }
</style>
