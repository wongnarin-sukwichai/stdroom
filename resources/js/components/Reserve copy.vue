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
                            name="home"
                            color="gray"
                            size="md"
                            class="pr-2"
                        ></box-icon>
                        <span class="text-xl py-1">จัดการห้อง</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Button -->
        <div class="flex justify-end">
            <button
                class="bg-gray-200 hover:bg-gray-300 shadow-lg rounded-md p-2 text-sm text-gray-700"
                @click="back()"
            >
                กลับหน้าหลัก
            </button>
        </div>

        <!-- Cards -->
        <div class="relative flex flex-col justify-center overflow-hidden py-4">
            <div class="flex mx-auto w-full justify-center">
                <div class="w-72">
                    <div
                        class="relative flex flex-col shadow-md rounded-xl overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 max-w-sm hover:cursor-pointer"
                    >
                        <div class="h-auto overflow-hidden">
                            <div class="h-36 overflow-hidden relative">
                                <img :src="conPath + conList.pic" alt="" />
                            </div>
                        </div>
                        <div class="bg-white py-4 px-3">
                            <h3 class="text-xs mb-2 font-medium">
                                {{ conList.title }}
                            </h3>
                            <div class="flex justify-between items-center">
                                <p class="text-xs text-gray-400">
                                    {{ conList.detail }}
                                </p>
                                <div
                                    class="relative z-40 flex items-center gap-2"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr />

        <!-- Table -->
        <div class="overflow-auto justify-center py-4">
            <table class="border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-4">#</th>
                        <th class="border p-4 w-48">รายละเอียด</th>
                        <th
                            class="border p-4"
                            v-for="(n, index) in isTime.total"
                            :key="index"
                        >
                            {{ isTime.hour + index }}{{ isTime.minute }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(room, index) in roomList" :key="index">
                        <td class="border">
                            <img :src="roomPath + room.pic" class="w-16 h-16" />
                        </td>
                        <td class="border p-4 text-center">
                            {{ room.title }}
                        </td>
                        <template v-if="room.status === 0">
                            <template
                                v-for="(n, index) in isTime.total"
                                :key="index"
                            >
                                <td
                                    v-if="chkReserve(room.id, index) === true"
                                    class="border p-4 text-center cursor-pointer"
                                    :class="
                                        chkConfirm(room.id, index) === 0
                                            ? 'bg-rose-300'
                                            : 'bg-green-300'
                                    "
                                    @click="
                                        showModal(room.id, room.title, index)
                                    "
                                ></td>
                                <td v-else class="border p-4"></td>
                            </template>
                        </template>
                        <template v-else>
                            <td
                                class="border p-4 bg-rose-300 text-center"
                                :colspan="isTime.total"
                            >
                                ** งดให้บริการชั่วคราว **
                            </td>
                        </template>
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
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                    >
                        <div class="grid grid-cols-2 bg-white px-4 sm:p-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-lime-100 sm:mx-0 sm:h-10 sm:w-10"
                                >
                                    <box-icon name="user"></box-icon>
                                </div>
                                <div
                                    class="text-center sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >ชื่อผู้จอง :
                                    </label>
                                    <div
                                        class="border-dotted border-2 rounded-lg p-2"
                                    >
                                        วงศ์นรินทร์ สุขวิชัย
                                    </div>
                                </div>
                            </div>

                            <div class="sm:flex sm:items-start">
                                <div
                                    class="text-center sm:ml-4 sm:text-left w-full"
                                >
                                    <label
                                        id="listbox-label"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >ยืนยัน :
                                    </label>
                                    <button
                                        class="relative inline-flex items-center justify-center p-4 px-6 py-2 overflow-hidden font-medium text-green-500 transition duration-300 ease-out border-2 border-green-400 rounded-full shadow-md group"
                                        :disabled="this.update.status === 1"
                                        @click="sendConfirm()"
                                    >
                                        <span
                                            class="absolute inset-0 flex items-center justify-center w-full h-full text-white duration-300 bg-green-400 group-hover:translate-x-0 ease"
                                            :class="
                                                this.update.status === 0
                                                    ? '-translate-x-full'
                                                    : ''
                                            "
                                        >
                                            <box-icon
                                                name="check"
                                                size="md"
                                                color="white"
                                            ></box-icon>
                                        </span>
                                        <span
                                            class="absolute flex items-center justify-center w-full h-full text-gray-400 transition-all duration-300 transform group-hover:translate-x-full ease"
                                            v-if="this.update.status === 0"
                                            >ยืนยัน</span
                                        >
                                        <span class="relative invisible"
                                            >ยืนยัน</span
                                        >
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-2 bg-white px-4 pt-2"
                            v-for="(record, index) in recordList"
                            :key="index"
                        >
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-200 sm:mx-0 sm:h-10 sm:w-10"
                                >
                                    <box-icon name="check"></box-icon>
                                </div>
                                <div
                                    class="text-center sm:ml-4 sm:text-left w-full"
                                >
                                    <div
                                        class="border-dotted border-2 rounded-lg p-2"
                                    >
                                        {{ record.name }} {{ record.surname }}
                                    </div>
                                </div>
                            </div>
                            <div
                                class="border-dotted border-2 rounded-lg ml-4 w-12 hover:border-gray-300 cursor-pointer flex items-center justify-center"
                                @click="delRecord(record.id, index)"
                            >
                                <box-icon name="x" color="#f87171"></box-icon>
                            </div>
                        </div>

                        <form @submit.prevent="send()">
                            <div class="bg-white px-4 sm:pt-4">
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-lime-100 sm:mx-0 sm:h-10 sm:w-10"
                                    >
                                        <box-icon
                                            type="logo"
                                            name="ok-ru"
                                        ></box-icon>
                                    </div>
                                    <div
                                        class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                    >
                                        <label
                                            id="listbox-label"
                                            class="block text-sm font-medium leading-6 text-gray-900"
                                        >
                                            ผู้เข้าร่วม :</label
                                        >

                                        <div
                                            x-show="open"
                                            class="flex items-center justify-between"
                                        >
                                            <input
                                                type="text"
                                                class="form-control block w-full px-3 py-1.5 rounded-l-lg text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-sky-300 focus:outline-none"
                                                placeholder="** รหัสนิสิต"
                                                required
                                                v-model="data.uid"
                                                :disabled="
                                                    this.update.status === 0
                                                "
                                            />
                                            <div
                                                class="flex items-center justify-center space-x-2"
                                            >
                                                <button
                                                    type="submit"
                                                    class="btn bg-sky-400 px-3 py-2.5 text-sm text-white hover:bg-sky-500 rounded-r-lg"
                                                >
                                                    บันทึก
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="grid grid-cols-2 mt-4">
                            <div
                                class="px-4 py-4 sm:px-6 bg-gradient-to-r from-indigo-100 via-purple-100 to-pink-100"
                            >
                                {{ this.roomTitle }}
                            </div>
                            <div
                                class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                            >
                                <button
                                    type="button"
                                    class="inline-flex w-full justify-center rounded-md bg-rose-400 px-3 py-2 text-sm text-white shadow-sm hover:bg-rose-500 sm:ml-3 sm:w-auto"
                                    @click="delReserve()"
                                >
                                    ยกเลิกห้อง
                                </button>
                                <button
                                    type="button"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm text-gray-900 shadow-sm hover:bg-gray-50 ring-1 ring-inset ring-gray-300 sm:mt-0 sm:w-auto"
                                    @click="close()"
                                >
                                    ออก
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";
import moment from "moment"; //format date thai
import "moment/dist/locale/th";
moment.locale("th");

export default {
    async mounted() {
        await this.getTime();
        this.getContainer();
        this.getRoom();
        this.isWeekend();
        this.getReserve();
    },
    data() {
        return {
            isModalShow: false,
            conPath: "../storage/containers/",
            roomPath: "../storage/rooms/",
            conList: "",
            roomList: "",
            timeList: "",
            reserveList: "",
            weekend: "",
            isTime: "",
            update: {
                id: "",
                status: "",
            },
            roomID: "",
            roomTitle: "",
            reserveTime: "",
            data: {
                today: moment().format("YYYY-MM-DD"),
                res_id: "",
                uid: "",
                name: "",
                surname: "",
            },
            recordList: "",
            dataCancel: {
                today: moment().format("YYYY-MM-DD"),
                room_id: "",
                time: "",
            },
        };
    },
    methods: {
        showModal(id, code, index) {
            this.roomID = id;
            this.roomTitle = code;
            this.reserveTime = index;
            this.update.id = this.getResID(id, index);
            this.update.status = this.chkConfirm(id, index);

            this.data.res_id = this.getResID(id, index);
            this.recordList = this.getRecord(this.getResID(id, index));

            this.dataCancel.room_id = id;
            this.dataCancel.time = index;

            this.isModalShow = true;
        },
        close() {
            this.isModalShow = false;
        },
        back() {
            this.$router.push("/dashboard");
        },
        async getContainer() {
            await axios
                .get("/api/container/" + this.$route.params.id)
                .then((response) => {
                    this.conList = response.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        async getTime() {
            await axios
                .get("/api/time")
                .then((response) => {
                    this.timeList = response.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        async getRoom() {
            await axios
                .get("/api/room/" + this.$route.params.id)
                .then((response) => {
                    this.roomList = response.data;
                })
                .catch((err) => {
                    console.log(err);
                });

            if (this.weekend == false) {
                this.isTime = this.setTimer(this.conList.time_1);
            } else {
                this.isTime = this.setTimer(this.conList.time_2);
            }
            // console.log(this.isTime.id)
        },
        getReserve() {
            var today = moment().format("YYYY-MM-DD");
            axios
                .get("../api/reserve/" + today)
                .then((response) => {
                    this.reserveList = response.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        isWeekend() {
            //เช็ค เสาร์-อาทิตย์
            var result = new Date();
            if (result.getDay() == 6 || result.getDay() == 0) {
                return (this.weekend = true);
            } else {
                return (this.weekend = false);
            }
        },
        setTimer(id) {
            //ดึงวัน time table
            var result;
            this.timeList.forEach((showTime) => {
                if (showTime.id == id) {
                    result = showTime;
                }
            });
            return result;
        },

        chkReserve(id, code) {
            var res = this.reserveList.some(
                (selection) =>
                    selection["room_id"] == id && selection["time"] == code
            );
            return res;
        },
        chkConfirm(id, code) {
            if (id != null && code != null) {
                var res = this.reserveList.find(
                    (selection) =>
                        selection["room_id"] == id && selection["time"] == code
                );
                return res.status;
            }
        },
        getResID(id, code) {
            if (id != null && code != null) {
                var res = this.reserveList.find(
                    (selection) =>
                        selection["room_id"] == id && selection["time"] == code
                );
                return res.id;
            }
        },
        sendConfirm() {
            axios
                .put("../api/reserve/" + this.update.id, this.update)
                .then((response) => {
                    var today = moment().format("YYYY-MM-DD");
                    axios.get("../api/reserve/" + today).then((response) => {
                        this.reserveList = response.data;
                    });

                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "บันทึกข้อมูลเรียบร้อย",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    this.update.status = response.data.status;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        async send() {
            try {
                await axios
                    .get(
                        "https://library.msu.ac.th/libapi/api/checkPatron/" +
                            this.data.uid
                    )
                    .then((response) => {
                        this.data.name = response.data[0].FNAMETHAI;
                        this.data.surname = response.data[0].LNAMETHAI;
                        // console.log(this.data.name);
                    })
                    .catch((err) => {
                        console.log(err);
                    });

                let timerInterval;
                await Swal.fire({
                    title: "กำลังตรวจสอบ...",
                    html: "กรุณารอ... <b></b>",
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    },
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        // console.log("I was closed by the timer");
                    }
                });

                if (this.data.name == null) {
                    Swal.fire({
                        title: "ผิดพลาด",
                        text: "ไม่พบข้อมูลสมาชิก กรุณาติดต่อเจ้าหน้าที่",
                        icon: "error",
                    });
                } else {
                    await axios
                        .post("/api/record", this.data)
                        .then((response) => {
                            this.recordList = response.data;
                        })
                        .catch((err) => {
                            console.log(err);
                        });
                }
            } catch (e) {
                return e;
            }
        },
        getRecord(id) {
            if (id != null) {
                axios
                    .get("/api/record/" + id)
                    .then((response) => {
                        this.recordList = response.data;
                        console.log(this.recordList);
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }
        },
        delRecord(id, index) {
            Swal.fire({
                title: "ยืนยันการลบ?",
                text: "ต้องการลบข้อมูลผู้เข้าใช้งานหรือไม่?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .delete("/api/record/" + id)
                        .then((response) => {
                            this.recordList.splice(index, 1);
                            Swal.fire({
                                title: "ลบข้อมูล!",
                                text: "ลบข้อมูลเรียบร้อย",
                                icon: "success",
                            });
                        })
                        .catch((err) => {
                            console.log(err);
                        });
                }
            });
        },
        delReserve() {
            Swal.fire({
                title: "ยืนยันการลบ?",
                text: "ต้องการลบข้อมูลจองห้องหรือไม่?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post("/api/deleteReserve", this.dataCancel)
                        .then((response) => {
                            this.getReserve();

                            this.isModalShow = false;

                            Swal.fire({
                                title: "ลบข้อมูล!",
                                text: "ลบข้อมูลเรียบร้อย",
                                icon: "success",
                            });
                        })
                        .catch((err) => {
                            console.log(err);
                        });
                }
            });
        },
    },
};
</script>
