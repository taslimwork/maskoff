<template>
    <div class="help-advice-rt-clm">
      <div class="faq-accordian-sec">
        <AccordionList>
          <AccordionItem class="faq-accordian-item" v-for="(item, index) in accordionItems" :key="index">
            <template #summary>
              <div class="faq-accordian-head">
                <h3 class="hFive">{{ item.question}}</h3>
                <i><img src="frontend_assets/images/acordian-drop.svg" alt=""></i>
              </div>
            </template>
            <div class="faq-accordian-body">
              <p>{{ item.answer }}</p>
            </div>
          </AccordionItem>
        </AccordionList>
      </div>
    </div>

       <!--
        <div class="help-advice-rt-clm">
            <div class="faq-accordian-sec">
                <div class="faq-accordian-item">
                    <div class="faq-accordian-head">
                        <h3 class="hFive">Who is eligible?</h3>
                        <i>
                            <img src="frontend_assets/images/acordian-drop.svg" alt="">
                        </i>
                    </div>
                    <div class="faq-accordian-body">
                        <p>Lorem ipsum dolor sit amet consectetur. Bibendum amet tincidunt metus vitae malesuada purus proin sit laoreet. Sed vulputate a nec tristique arcu feugiat ultrices velit.</p>
                    </div>
                </div>
            </div>
        </div>
    -->
  </template>

  <script setup>
    import { AccordionList, AccordionItem } from "vue3-rich-accordion";
    const {accordionItems} = defineProps(['accordionItems']);
  </script>

<style scoped>
    .faq-accordian-body {
        display: block;
    }

    .accordion-item {
    background-color: #fff;
      border: none;
    }
    .faq-accordian-item {
        border-bottom: 1px solid rgba(18, 85, 255, 0.13) !important;
    }
    .accordion-item--open h3::before {
        background: var(--theme-blue);
    }

    .accordion-item--open i {
        transform: rotate(180deg);
    }

    .accordion-item summary {
        list-style: none;
    }
</style>
