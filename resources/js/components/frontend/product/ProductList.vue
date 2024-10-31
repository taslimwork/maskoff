<template>
    <div class="handset_item">
        <div class="handset_item_top">
            <h5>Apple iphone 14</h5>
            <div class="request_btn_wrap">
                <a href="#url" class="cmn_btn2 request_btn">
                    <img src="frontend_assets/images/request-btn-icon.svg" alt="">
                    <span>On Request</span>
                </a>
            </div>
            <figure class="handset_item_top_logo">
                <img src="frontend_assets/images/vodafone-logo.png" alt="">
            </figure>
        </div>
        <div class="handset_product_wrap">
            <div class="row handset_item_product_row">
                <div class="col-auto handset_item_product_col_left">
                    <div class="handset_slider_wrapper">
                        <!-- <span class="pagingInfo handset_img_count"></span> -->
                        <div class="slideshow handset_img_slider">
                            <ProductImageSlider :images="images"/>
                        </div>
                    </div>
                </div>
                <div class="col-auto handset_item_product_col_rght">
                    <div class="handset-details-top">
                        <p class="provided_bt_txt">Provided by</p>
                        <span class="handset_product_tag">fonehouse</span>
                        <a href="#url" class="wishlist_btn">
                            <i class="fas fa-heart"></i>
                        </a>
                    </div>
                    <ul class="handset-details-list">
                        <li><img src="frontend_assets/images/handset-details-icon1.svg" alt="">Unlimited text</li>
                        <li><img src="frontend_assets/images/handset-details-icon2.svg" alt="">£200 up front cost</li>
                        <li><img src="frontend_assets/images/handset-details-icon3.svg" alt="">12 month contract</li>
                        <li><img src="frontend_assets/images/data-icon.svg" alt="">Data</li>
                        <li><img src="frontend_assets/images/handset-details-icon4.svg" alt="">250+minutes</li>
                        <li><img src="frontend_assets/images/handset-details-icon5.svg" alt="">Blue Light Discount</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="handset_item_bottom">
            <div class="bty_now_btn_wrapper">
                <a href="#url" class="cmn_btn bty_now_btn">Buy Now</a>
            </div>
            <div class="handset_item_price_outer">
                <div class="price_wrapper">
                    <p class="price_txt">$29.99</p>
                    <img src="frontend_assets/images/request-btn-icon.svg" class="price_icon" alt="">
                </div>
                <p class="price_discount_txt">Include Blue Light Discount of 25%</p>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import ProductImageSlider from './ProductImageSlider.vue'

const images = ref({});


</script>
