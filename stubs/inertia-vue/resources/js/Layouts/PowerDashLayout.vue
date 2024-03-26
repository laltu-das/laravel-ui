<script setup>
import {ref} from 'vue';
import Modal from "@/Components/Core/Modal/Modal.vue";
import Button from "@/Components/Core/Button/Button.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import {Link} from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import AdminSidebar from "@/Components/Core/Sidebar/AdminSidebar.vue";
import SidebarItem from "@/Components/Core/Sidebar/SidebarItem.vue";
import SidebarDropdownItem from "@/Components/Core/Sidebar/SidebarDropdownItem.vue";

const showingNavigationDropdown = ref(false);
const isShowModal = ref(false)

</script>

<template>
    Power Dash
  <main>
    <slot/>
  </main>

</template>
