<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="flex card-header">
                        <box-icon
                            name="dots-vertical-rounded"
                            size="md"
                            class="pr-2"
                        ></box-icon>
                        <box-icon
                            name="time"
                            color="gray"
                            size="md"
                            class="pr-2"
                        ></box-icon>
                        <span class="text-xl py-1">เวลาให้บริการ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Button -->
        <div class="flex justify-end">
            <button
                class="bg-green-300 hover:bg-green-400 shadow-lg rounded-md p-2 text-sm text-gray-700"
                @click="showModal()"
            >
                เพิ่มข้อมูล
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-auto mt-4">
            <table class="border-collapse border w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-4 w-12">ID</th>
                        <th class="border p-4">รายละเอียด</th>
                        <th class="border p-4 w-48">จำนวนชั่วโมง/วัน</th>
                        <th class="border p-4">เวลาเริ่มต้น</th>
                        <th class="border p-4">สิ้นสุด</th>
                        <th class="border p-4">แก้ไขล่าสุด</th>
                        <th class="border p-4">วันที่แก้ไข</th>
                        <th class="border p-4">เวลาแก้ไข</th>
                        <th class="border p-4">#</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        class="text-center"
                        v-for="(time, index) in timeList"
                        :key="index"
                    >
                        <td class="border p-4">{{ time.id }}</td>
                        <td class="border p-4">{{ time.title }}</td>
                        <td class="border p-4">{{ time.total }}</td>
                        <td class="border p-4">{{ time.start }}</td>
                        <td class="border p-4">{{ time.end }}</td>
                        <td class="border p-4">{{ time.created }}</td>
                        <td class="border p-4">
                            {{ moment(time.created_at).format("LL") }}
                        </td>
                        <td class="border p-4">
                            {{ moment(time.created_at).format("LTS") }} น.
                        </td>
                        <td class="border p-4">
                            <div class="flex justify-center">
                                <div>
                                    <box-icon
                                        name="cog"
                                        color="#94a3b8"
                                        size="sm"
                                        animation="tada-hover"
                                        class="cursor-pointer"
                                        @click="getEdit(time.id)"
                                    ></box-icon>
                                </div>
                                <!-- <div class="pl-2">
                                    <box-icon
                                        name="trash"
                                        color="#f87171"
                                        size="sm"
                                        animation="tada-hover"
                                        class="cursor-pointer"
                                        @click="del(time.id, index)"
                                    ></box-icon>
                                </div> -->
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Show -->
    <transition name="fade" mode="out-in">
        <div
            class="relative z-10"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
            v-show="isModalShow"
        >
            <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            ></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <form
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        @submit.prevent="send()"
                    >
                        <div
                            class="grid grid-cols-2 bg-white px-4 pb-4 sm:p-4 sm:pb-4"
                        >
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10"
                                >
                                    <box-icon
                                        name="message-rounded-dots"
                                    ></box-icon>
                                </div>
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                    >
                                        รายละเอียด :</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                        required
                                        v-model="data.title"
                                    />
                                </div>
                            </div>

                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >จำนวนชั่วโมง/วัน :
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                        placeholder="10"
                                        required
                                        v-model="data.total"
                                    />
                                </div>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-2 bg-white px-4 pb-4 sm:p-4 sm:pb-4"
                        >
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10"
                                >
                                    <box-icon name="time"></box-icon>
                                </div>
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >เวลาเริ่มต้น : ชั่วโมง
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                        placeholder="8"
                                        required
                                        v-model="data.hour"
                                    />
                                </div>
                            </div>

                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >นาที :
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                        placeholder=".00 น."
                                        required
                                        v-model="data.minute"
                                    />
                                </div>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-2 bg-white px-4 pb-4 sm:p-4 sm:pb-4"
                        >
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10"
                                >
                                    <box-icon name="timer"></box-icon>
                                </div>
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >เวลาเริ่มต้น :
                                    </label>
                                    {{ start }}
                                </div>
                            </div>

                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >เวลาสิ้นสุด :
                                    </label>
                                    {{ end }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                        >
                            <button
                                type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-green-500 px-3 py-2 text-sm text-white shadow-sm hover:bg-green-600 sm:ml-3 sm:w-auto"
                            >
                                บันทึก
                            </button>
                            <button
                                type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm text-gray-900 shadow-sm hover:bg-gray-50 ring-1 ring-inset ring-gray-300 sm:mt-0 sm:w-auto"
                                @click="close()"
                            >
                                ออก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </transition>

    <!-- Modal Edit -->
    <transition name="fade" mode="out-in">
        <div
            class="relative z-10"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
            v-show="isModalEdit"
        >
            <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            ></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <form
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        @submit.prevent="update()"
                    >
                        <div
                            class="grid grid-cols-2 bg-white px-4 pb-4 sm:p-4 sm:pb-4"
                        >
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10"
                                >
                                    <box-icon
                                        name="message-rounded-dots"
                                    ></box-icon>
                                </div>
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                    >
                                        รายละเอียด :</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                        required
                                        v-model="dataEdit.title"
                                    />
                                </div>
                            </div>

                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >จำนวนชั่วโมง/วัน :
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                        placeholder="10"
                                        required
                                        v-model="dataEdit.total"
                                    />
                                </div>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-2 bg-white px-4 pb-4 sm:p-4 sm:pb-4"
                        >
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10"
                                >
                                    <box-icon name="time"></box-icon>
                                </div>
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >เวลาเริ่มต้น : ชั่วโมง
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                        placeholder="8"
                                        required
                                        v-model="dataEdit.hour"
                                    />
                                </div>
                            </div>

                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >นาที :
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                        placeholder=".00 น."
                                        required
                                        v-model="dataEdit.minute"
                                    />
                                </div>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-2 bg-white px-4 pb-4 sm:p-4 sm:pb-4"
                        >
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10"
                                >
                                    <box-icon name="timer"></box-icon>
                                </div>
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >เวลาเริ่มต้น :
                                    </label>
                                    {{ startEdit }}
                                </div>
                            </div>

                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >เวลาสิ้นสุด :
                                    </label>
                                    {{ endEdit }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                        >
                            <button
                                type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-amber-400 px-3 py-2 text-sm text-white shadow-sm hover:bg-amber-500 sm:ml-3 sm:w-auto"
                            >
                                ปรับปรุงข้อมูล
                            </button>
                            <button
                                type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm text-gray-900 shadow-sm hover:bg-gray-50 ring-1 ring-inset ring-gray-300 sm:mt-0 sm:w-auto"
                                @click="closeEdit()"
                            >
                                ออก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import "boxicons";
import Swal from "sweetalert2";
import moment from "moment"; //format date thai
import "moment/dist/locale/th";
moment.locale("th");

export default {
    mounted() {
        this.getTime();
    },
    data() {
        return {
            isModalShow: false,
            isModalEdit: false,
            timeList: "",
            moment: moment,
            data: {
                title: "",
                total: "",
                hour: "",
                minute: "",
                start: "",
                end: "",
            },
            dataEdit: {
                id: "",
                title: "",
                total: "",
                hour: "",
                minute: "",
                start: "",
                end: "",
            },
        };
    },
    methods: {
        showModal() {
            this.isModalShow = true;
        },
        close() {
            this.isModalShow = false;
        },
        closeEdit() {
            this.isModalEdit = false;
        },
        getTime() {
            axios
                .get("/api/time")
                .then((response) => {
                    this.timeList = response.data;
                    console.log(this.timeList);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        async send() {
            try {
                // this.data.end = await this.calTime(
                //     this.data.start,
                //     this.data.time
                // );
                await this.$store.dispatch("storeTime", this.data);
                await Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "บันทึกข้อมูลเรียบร้อย",
                    showConfirmButton: false,
                    timer: 1500,
                });
                this.isModalShow = false;
                this.getTime();
            } catch (err) {
                console.log(err);
            }
        },
        // calTime(num, time) {
        //     let res = num.substring(0, 2);
        //     let last = num.substring(2, 5);
        //     let result = parseInt(res) + parseInt(time);

        //     if (result < 10) {
        //         return result.toString().padStart(2, 0) + last;
        //     } else {
        //         return result + last;
        //     }
        // },
        del(id, index) {
            Swal.fire({
                title: "ต้องการลบข้อมูล?",
                text: "ยืนยันการลบข้อมูลหรือไม่",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .delete("/api/time/" + id)
                        .then((response) => {
                            //console.log(res);
                        })
                        .catch((err) => {
                            //console.log(err);
                        });
                    this.timeList.splice(index, 1);
                    Swal.fire("ลบข้อมูล!", "ลบข้อมูลเรียบร้อย", "success");
                }
            });
        },
        getEdit(id) {
            this.isModalEdit = true;
            axios
                .get("/api/time/" + id)
                .then((response) => {
                    this.dataEdit.id = response.data.id;
                    this.dataEdit.title = response.data.title;
                    this.dataEdit.total = response.data.total;
                    this.dataEdit.hour = response.data.hour;
                    this.dataEdit.minute = response.data.minute;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        async update() {
            try {
                // this.dataEdit.end = await this.calTime(
                //     this.dataEdit.start,
                //     this.dataEdit.time
                // );
                await this.$store.dispatch("updateTime", this.dataEdit);
                await Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "บันทึกข้อมูลเรียบร้อย",
                    showConfirmButton: false,
                    timer: 1500,
                });
                this.isModalEdit = false;
                this.getTime();
            } catch (err) {
                console.log(err);
            }
        },
    },
    computed: {
        start() {
            this.data.start = this.data.hour + this.data.minute;
            return this.data.start;
        },
        end() {
            this.data.end = (parseInt(this.data.total) + parseInt(this.data.hour)) + this.data.minute;
            return this.data.end;
        },
        startEdit() {
            this.dataEdit.start = this.dataEdit.hour + this.dataEdit.minute;
            return this.dataEdit.start;
        },
        endEdit() {
            this.dataEdit.end = (parseInt(this.dataEdit.total) + parseInt(this.dataEdit.hour)) + this.dataEdit.minute;
            return this.dataEdit.end;
        },
    },
};
</script>
