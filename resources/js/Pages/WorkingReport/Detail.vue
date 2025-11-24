<script setup>
import { onMounted, onUnmounted, nextTick, ref, computed, toRef } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Select from '@vueform/multiselect'
import Button from '@/Components/Button.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'
import TextArea from '@/Components/TextArea.vue'
import Swal from 'sweetalert2'
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import Icon from '@/Components/Icon.vue'
import axios from 'axios';
import BtnAttachment from '@/Components/Button/Attachment.vue'
import AttachmentInline from '@/Components/Button/AttachmentInline.vue'

import { result } from 'lodash'
// import moment from 'moment';

const props = defineProps({
    report: Object,
    checksheet: Array,
    checksheetday: Array,
    checksheetworkresult: Array,
    warmingup: Array,
    workresult: Array,
    machines: Array,
    regions: Array,
    users: Array,
    warmingup_user: Array,
    workresult_user: Array,
    masters: Array,
    results: Array,
    upload: Array,
})

const { user } = usePage().props.value

const form = useForm({
    id: props.report?.id || null,
    machine_id: props.report?.machine_id || null,
    region_id: props.report?.region_id || null,
    date: props.report?.date || null,
    has_trouble: props.report?.has_trouble || null,
    status: props.report?.status || null,
});

const form1 = useForm({
    id: props.checksheetday?.id || null,
    working_report_id: props.report?.id || null,
    no_seri: props.checksheetday?.no_seri || null,
    jenis: props.checksheetday?.jenis || null,
    jam_mesin: props.checksheetday?.jam_mesin || null,
    counter_pecok: props.checksheetday?.counter_pecok || null,
    kilometer_mesin: props.checksheetday?.kilometer_mesin || null,
    tanggal: props.checksheetday?.tanggal || null,
    lokasi: props.checksheetday?.lokasi || null,
    wilayah: props.checksheetday?.wilayah || null,
    region_id: props.checksheetday?.region_id || null,
    note: props.checksheetday?.note || null,
    results: props.results ?? [],
});

const form2 = useForm({
  id: props.checksheetworkresult?.id ?? null,
  working_report_id: props.report?.id || null,
  check_sheet_day_id: props.checksheetday?.id ?? null,
  catatan_gangguan: props.checksheetworkresult?.catatan_gangguan ?? '',
  lokasi_dan_jam1: props.checksheetworkresult?.lokasi_dan_jam1 ?? '',
  hu_hi_1: props.checksheetworkresult?.hu_hi_1 ?? '',
  jumlah_1: props.checksheetworkresult?.jumlah_1 ?? '',
  lokasi_dan_jam2: props.checksheetworkresult?.lokasi_dan_jam2 ?? '',
  hu_hi_2: props.checksheetworkresult?.hu_hi_2 ?? '',
  jumlah_2: props.checksheetworkresult?.jumlah_2 ?? '',
  lokasi_dan_jam3: props.checksheetworkresult?.lokasi_dan_jam3 ?? '',
  hu_hi_3: props.checksheetworkresult?.hu_hi_3 ?? '',
  jumlah_3: props.checksheetworkresult?.jumlah_3 ?? '',
  operator_by1: props.checksheetworkresult?.operator_by1 ?? '',
  operator_by2: props.checksheetworkresult?.operator_by2 ?? '',
  operator_by3: props.checksheetworkresult?.operator_by3 ?? '',
  operator_by4: props.checksheetworkresult?.operator_by4 ?? '',
  operator_at1: props.checksheetworkresult?.operator_at1 ?? '',
  operator_at2: props.checksheetworkresult?.operator_at2 ?? '',
  operator_at3: props.checksheetworkresult?.operator_at3 ?? '',
  operator_at4: props.checksheetworkresult?.operator_at4 ?? '',
  validasi1: props.checksheetworkresult?.validasi1 ?? '',
  validasi2: props.checksheetworkresult?.validasi2 ?? '',
  validasi3: props.checksheetworkresult?.validasi3 ?? '',
  validasi4: props.checksheetworkresult?.validasi4 ?? '',
});

// onMounted(() => {
//   console.log("Loaded Results:", props.checksheetworkresult);
// });

const form3 = useForm({
    id: props.warmingup?.id || null,
    working_report_id: props.report?.id || null,
    machine_id: props.warmingup?.machine_id || null,
    waktu_start_engine: props.warmingup?.waktu_start_engine || null,
    jam_kerja: props.warmingup?.jam_kerja || null,
    jam_mesin: props.warmingup?.jam_mesin || null,
    jam_genset: props.warmingup?.jam_genset || null,
    counter_pecok: props.warmingup?.counter_pecok || null,
    oddometer: props.warmingup?.oddometer || null,
    waktu_stop_engine: props.warmingup?.waktu_stop_engine || null,
    penggunaan_hsd: props.warmingup?.penggunaan_hsd || null,
    hsd_tersedia: props.warmingup?.hsd_tersedia || null,
    note: props.warmingup?.note || null,
    user_id: props.warmingup?.warmingup_user.map(warmingup_user => warmingup_user.user_id) || [],
});

// const form4 = useForm({
//     id: props.workresult?.id || null,
//     working_report_id: props.report?.id || null,
//     machine_id: props.workresult?.machine_id || null,
//     region_id: props.workresult?.region_id || null,
//     antara: props.workresult?.antara || null,
//     km_hm: props.workresult?.km_hm || null,
//     jumlah_msp: props.workresult?.jumlah_msp || null,
//     waktu_start_engine: props.workresult?.waktu_start_engine || null,
//     jam_luncuran: props.workresult?.jam_luncuran || null,
//     jam_kerja: props.workresult?.jam_kerja || null,
//     jam_mesin: props.workresult?.jam_mesin || null,
//     jam_genset: props.workresult?.jam_genset || null,
//     counter_pecok: props.workresult?.counter_pecok || null,
//     oddometer: props.workresult?.oddometer || null,
//     penggunaan_hsd: props.workresult?.penggunaan_hsd || null,
//     hsd_tersedia: props.workresult?.hsd_tersedia || null,
//     pengawal_id: props.workresult?.pengawal_id || null,
//     note: props.workresult?.note || null,
//     user_id: props.workresult?.workresult_user.map(workresult_user => workresult_user.user_id) || [],
// });

const form4 = useForm({
    id: props.workresult?.id || null,
    working_report_id: props.report?.id || null,
    machine_id: props.workresult?.machine_id || null,
    region_id: props.workresult?.region_id || null,
    tanggal: props.workresult?.tanggal || null,
    cuaca: props.workresult?.cuaca || null,
    wilayah: props.workresult?.wilayah || null,
    petak_jalan: props.workresult?.petak_jalan || null,
    jalur: props.workresult?.jalur || null,
    kelas_jalan: props.workresult?.kelas_jalan || null,
    kecepatan_lintas: props.workresult?.kecepatan_lintas || null,
    lokasi_awal1: props.workresult?.lokasi_awal1 || null,
    lokasi_akhir1: props.workresult?.lokasi_akhir1 || null,
    jumlah1: props.workresult?.jumlah1 || null,
    lokasi_awal2: props.workresult?.lokasi_awal2 || null,
    lokasi_akhir2: props.workresult?.lokasi_akhir2 || null,
    jumlah2: props.workresult?.jumlah2 || null,
    lokasi_awal3: props.workresult?.lokasi_awal3 || null,
    lokasi_akhir3: props.workresult?.lokasi_akhir3 || null,
    jumlah3: props.workresult?.jumlah3 || null,
    total_distance: props.workresult?.total_distance || null,
    no_wesel1: props.workresult?.no_wesel1 || null,
    km_hm1: props.workresult?.km_hm1 || null,
    jumlah_wesel1: props.workresult?.jumlah_wesel1 || null,
    no_wesel2: props.workresult?.no_wesel2 || null,
    km_hm2: props.workresult?.km_hm2 || null,
    jumlah_wesel2: props.workresult?.jumlah_wesel2 || null,
    no_wesel3: props.workresult?.no_wesel3 || null,
    km_hm3: props.workresult?.km_hm3 || null,
    jumlah_wesel3: props.workresult?.jumlah_wesel3 || null,
    total_wesel: props.workresult?.total_wesel || null,
   waktu_start_engine: props.workresult?.waktu_start_engine || null,
    waktu_stop_engine: props.workresult?.waktu_stop_engine || null,
    jam_travelling_awal: props.workresult?.jam_travelling_awal || null,
    jam_travelling_akhir: props.workresult?.jam_travelling_akhir || null,
    jam_kerja_awal: props.workresult?.jam_kerja_awal || null,
    jam_kerja_akhir: props.workresult?.jam_kerja_akhir || null,
    jam_mesin_awal: props.workresult?.jam_mesin_awal || null,
    jam_mesin_akhir: props.workresult?.jam_mesin_akhir || null,
    jam_generator_awal: props.workresult?.jam_generator_awal || null,
    jam_generator_akhir: props.workresult?.jam_generator_akhir || null,
    counter_tamping: props.workresult?.counter_tamping || null,
    oddometer_ops: props.workresult?.oddometer_ops || null, // 'oddometer' sdh ada di atas, ganti nama
    hsd_awal_kerja: props.workresult?.hsd_awal_kerja || null,
    hsd_akhir_kerja: props.workresult?.hsd_akhir_kerja || null,
    hsd_konsumsi: props.workresult?.hsd_konsumsi || null,
    oddometer_hsd: props.workresult?.oddometer_hsd || null,
    pengawal_id: props.workresult?.pengawal_id || null,
    note: props.workresult?.note || null,
    operator_by1: props.workresult?.operator_by1 || null,
    operator_at1: props.workresult?.operator_at1 || null,
    operator_by2: props.workresult?.operator_by2 || null,
    operator_at2: props.workresult?.operator_at2 || null,
    operator_by3: props.workresult?.operator_by3 || null,
    operator_at3: props.workresult?.operator_at3 || null,
});

const form5 = useForm({
    id: props.upload?.id || null,
    working_report_id: props.report?.id || null,
    date: props.upload?.date || null,
});

const render = ref(true)
const table = ref(null)
const open = ref(false)
const show = () => open.value = true

const close = () => {
  form4.reset()
  render.value = false
  nextTick(() => {
    nextTick(() => open.value = false)
    render.value = true
  })
}

const currentStep = ref(1)
const currentStep1 = ref(1)
const showNextButton = ref(false)
const currentGroupIndex = ref(0)

const groups = computed(() => {
  const uniqueGroups = [...new Set(props.results.map(r => r.group_name))]
  return uniqueGroups
})

const currentGroupResults = computed(() => {
  return props.results.filter(r => r.group_name === groups.value[currentGroupIndex.value])
})

const nextGroup = () => {
  if (currentGroupIndex.value === groups.value.length - 1) {
    currentStep.value = 3
  } else if (currentGroupIndex.value < groups.value.length - 1) {
    currentGroupIndex.value++
  }
}

const goToStep1 = () => {
  if (currentStep.value === 2) {
    currentStep.value = 1
  }
}

const prevGroup = () => {
  if (currentGroupIndex.value > 0) {
    currentGroupIndex.value--
  }
  else if (currentStep.value === 2) {
    goToStep1()
  }
}

const submitchecksheetday = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form1.post(route('check-sheet-day.store', props.report.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data berhasil disimpan!',
        timer: 1000,
        showConfirmButton: false,
      });
      setTimeout(() => window.location.reload(), 1000);
      showNextButton.value = true
    },
    onError: () => {
      show();
      Swal.hideLoading();
      Swal.fire({
        title: 'Terjadi kesalahan!',
        text: 'Gagal menyimpan data.',
        icon: 'error',
      });
    },
  })
}

const updatechecksheetday = async () => {
  try {
    const response = await Swal.fire({
      title: 'Konfirmasi',
      html: `Apakah anda yakin akaan ubah data ini?`,
      icon: 'question',
      showCancelButton: true,
      showCloseButton: true,
    });

    if (response.isConfirmed) {
      Swal.fire({
        title: 'Sedang menyimpan data...',
        didOpen: () => {
          Swal.showLoading();
        },
        allowOutsideClick: false,
      });

      const result = await form1.patch(route('check-sheet-day.update', form1.id), {
        onSuccess: () => {
          close();
          Swal.showLoading();
          Swal.fire({
            title: 'Data berhasil diupdate!',
            icon: 'success',
          });
        },
        onError: () => {
          Swal.showLoading();
          Swal.fire({
            title: 'Terjadi kesalahan!',
            text: 'Gagal menyimpan data.',
            icon: 'error',
          });
        },
      });

      return result;
    }
  } catch (error) {
    console.error(error);
  }
};

const toggleResult = async (item, field) => {
  const previousValue = item[field];
  item[field] = item[field] == 1 ? 0 : 1;

  try {
    const response = await axios.post(route("checksheetday-results.autosave"), {
      check_sheet_day_id: form1.id,
      check_sheet_master_day_id: item.check_sheet_master_day_id,
      cek: item.cek ?? 0,
      tambahan: item.tambahan ?? 0,
      ganti: item.ganti ?? 0,
      kiri_depan: item.kiri_depan ?? '',
      kanan_depan: item.kanan_depan ?? '',
      keterangan: item.keterangan ?? '',
    });

    // console.log(`Autosaved ${field}: ${item[field]}`);

    Swal.fire({
      icon: "success",
      title: previousValue == null ? "Berhasil Disimpan" : "Berhasil Diperbarui",
      timer: 1000,
      showConfirmButton: false,
    });
  } catch (error) {
    console.error("Autosave failed:", error);
    Swal.fire("Error", "Gagal menyimpan data!", "error");
  }
};

const saveTextField = async (item) => {
  try {
    await axios.post(route("checksheetday-results.autosave"), {
      check_sheet_day_id: form1.id,
      check_sheet_master_day_id: item.check_sheet_master_day_id,
      cek: item.cek ?? 0,
      tambahan: item.tambahan ?? 0,
      ganti: item.ganti ?? 0,
      kiri_depan: item.kiri_depan ?? '',
      kanan_depan: item.kanan_depan ?? '',
      keterangan: item.keterangan ?? '',
    });

    Swal.fire({
      icon: "success",
      title: "Berhasil disimpan",
      timer: 1000,
      showConfirmButton: false,
    });
  } catch (error) {
    console.error("Autosave failed:", error);
    Swal.fire("Error", "Gagal menyimpan data!", "error");
  }
};

const autosaveUpload = async () => {
  try {
    await axios.post(route('upload.autosave'), {
      working_report_id: form5.working_report_id,
      date: form5.date,
    })

    Swal.fire({
      toast: true,
      position: 'bottom-end',
      icon: 'success',
      title: 'Data tersimpan otomatis',
      showConfirmButton: false,
      timer: 1000,
    }).then(() => {
      window.location.reload()
    })
  } catch (error) {
    console.error(error)
    Swal.fire({
      toast: true,
      position: 'bottom-end',
      icon: 'error',
      title: 'Gagal menyimpan data',
      showConfirmButton: false,
      timer: 1500,
    })
  }
}

// const saveWorkResult = async () => {
//   try {
//     await axios.post(route("checksheet-workresult.autosave"), {
//       id: form2.id,
//       working_report_id: form2.working_report_id,
//       check_sheet_day_id: form2.check_sheet_day_id,
//       catatan_gangguan: form2.catatan_gangguan,
//       lokasi_dan_jam1: form2.lokasi_dan_jam1,
//       hu_hi_1: form2.hu_hi_1,
//       jumlah_1: form2.jumlah_1,
//       lokasi_dan_jam2: form2.lokasi_dan_jam2,
//       hu_hi_2: form2.hu_hi_2,
//       jumlah_2: form2.jumlah_2,
//       lokasi_dan_jam3: form2.lokasi_dan_jam3,
//       hu_hi_3: form2.hu_hi_3,
//       jumlah_3: form2.jumlah_3,
//       // operator_by1: form2.operator_by1,
//       // operator_by2: form2.operator_by2,
//       // operator_by3: form2.operator_by3,
//       // operator_by4: form2.operator_by4,
//       operator_by1: form2.operator_by1?.value ?? form2.operator_by1 ?? null,
//       operator_by2: form2.operator_by2?.value ?? form2.operator_by2 ?? null,
//       operator_by3: form2.operator_by3?.value ?? form2.operator_by3 ?? null,
//       operator_by4: form2.operator_by4?.value ?? form2.operator_by4 ?? null,
//       validasi1: form2.validasi1,
//       validasi2: form2.validasi2,
//       validasi3: form2.validasi3,
//       validasi4: form2.validasi4,
//     })

//     Swal.fire({
//       icon: "success",
//       title: "Berhasil disimpan otomatis",
//       timer: 1000,
//       showConfirmButton: false,
//     })
//   } catch (error) {
//     console.error("Autosave gagal:", error)
//     Swal.fire("Error", "Gagal menyimpan data!", "error")
//   }
// }

const submitchecksheetworkresult = async () => {
  try {
    await axios.post(route("checksheet-workresult.store"), form2)
    Swal.fire({
      icon: "success",
      title: 'Berhasil!',
      text: 'Data berhasil disimpan!',
      timer: 1500,
      showConfirmButton: false,
    });
    setTimeout(() => window.location.reload(), 1000);
  } catch (error) {
    console.error(error)
    Swal.fire("Gagal", "Terjadi kesalahan saat menyimpan data", "error")
  }
}

const updatechecksheetworkresult = async () => {
  try {
    const response = await Swal.fire({
      title: 'Konfirmasi',
      html: `Apakah anda yakin akan ubah data ini?`,
      icon: 'question',
      showCancelButton: true,
      showCloseButton: true,
      confirmButtonText: 'Ya, ubah',
      cancelButtonText: 'Batal',
    });

    if (!response.isConfirmed) return;

    Swal.fire({
      title: 'Sedang menyimpan data...',
      didOpen: () => {
        Swal.showLoading();
      },
      allowOutsideClick: false,
    });

    await form2.patch(route('checksheet-workresult.update', form2.id), {
      onSuccess: () => {
        Swal.fire({
          icon: 'success',
          title: 'Data berhasil diperbarui!',
          timer: 1500,
          showConfirmButton: false,
        });
      },
      onError: () => {
        Swal.fire({
          icon: 'error',
          title: 'Terjadi kesalahan!',
          text: 'Gagal menyimpan data.',
        });
      },
    });
  } catch (error) {
    console.error(error);
    Swal.fire({
      icon: 'error',
      title: 'Kesalahan!',
      text: 'Terjadi kesalahan tak terduga.',
    });
  }
};

const canApprove = computed(() => {
  const result = props.checksheetday?.checksheetworkresult
  if (!result || !user?.id) return false

  return (
    (result.operator_by1 === user.id && !result.operator_at1) ||
    (result.operator_by2 === user.id && !result.operator_at2) ||
    (result.operator_by3 === user.id && !result.operator_at3) ||
    (result.operator_by4 === user.id && !result.operator_at4)
  )
})

const approvechecksheetworkresult = async () => {
  const result = props.checksheetday?.checksheetworkresult
  if (!result) return

  const confirm = await Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda akan menyetujui data ini.",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, setujui",
    cancelButtonText: "Batal",
  });
    setTimeout(() => window.location.reload(), 1000);

  if (!confirm.isConfirmed) return

  try {
    const res = await axios.post(route("checksheet-workresult.approve"), {
      id: result.id,
    })

    const now = new Date().toISOString()
    if (result.operator_by1 === user.id) result.operator_at1 = now
    if (result.operator_by2 === user.id) result.operator_at2 = now
    if (result.operator_by3 === user.id) result.operator_at3 = now
    if (result.operator_by4 === user.id) result.operator_at4 = now

    Swal.fire({
      icon: "success",
      title: res.data.message || "Berhasil disetujui!",
      timer: 1500,
      showConfirmButton: false,
    })
  } catch (error) {
    console.error(error)
    Swal.fire({
      icon: "error",
      title: "Gagal approve!",
      text: error.response?.data?.message || error.message || "Terjadi kesalahan saat menyetujui.",
    })
  }
}

const submitwarmingup = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form3.post(route('warming-up.store', props.report.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data berhasil disimpan.',
        timer: 1500,
        showConfirmButton: false,
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Terjadi kesalahan saat menyimpan data.',
      })
    },
  })
}

const updatewarmingup = async () => {
  try {
    const response = await Swal.fire({
      title: 'Konfirmasi',
      html: `Apakah anda yakin akaan ubah data ini?`,
      icon: 'question',
      showCancelButton: true,
      showCloseButton: true,
    });

    if (response.isConfirmed) {
      Swal.fire({
        title: 'Sedang menyimpan data...',
        didOpen: () => {
          Swal.showLoading();
        },
        allowOutsideClick: false,
      });

      const result = await form3.patch(route('warming-up.update', form3.id), {
        onSuccess: () => {
          close();
          Swal.showLoading();
          Swal.fire({
            title: 'Data berhasil diupdate!',
            icon: 'success',
          });
        },
        onError: () => {
          Swal.showLoading();
          Swal.fire({
            title: 'Terjadi kesalahan!',
            text: 'Gagal menyimpan data.',
            icon: 'error',
          });
        },
      });

      return result;
    }
  } catch (error) {
    console.error(error);
  }
};

const submitworkresult = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form4.post(route('work-results.store', props.report.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Laporan kerja berhasil disimpan.',
        timer: 1500,
        showConfirmButton: false,
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Terjadi kesalahan saat menyimpan data.',
      })
    },
  })
}

const updateworkresult = async () => {
  try {
    const response = await Swal.fire({
      title: 'Konfirmasi',
      html: `Apakah anda yakin akaan ubah data ini?`,
      icon: 'question',
      showCancelButton: true,
      showCloseButton: true,
    });

    if (response.isConfirmed) {
      Swal.fire({
        title: 'Sedang menyimpan data...',
        didOpen: () => {
          Swal.showLoading();
        },
        allowOutsideClick: false,
      });

      const result = await form4.patch(route('work-results.update', form4.id), {
        onSuccess: () => {
          close();
          Swal.showLoading();
          Swal.fire({
            title: 'Data berhasil diupdate!',
            icon: 'success',
          });
        },
        onError: () => {
          Swal.showLoading();
          Swal.fire({
            title: 'Terjadi kesalahan!',
            text: 'Gagal menyimpan data.',
            icon: 'error',
          });
        },
      });

      return result;
    }
  } catch (error) {
    console.error(error);
  }
};

const canApproveWorkResult = computed(() => {
  const workresult = props.report?.workresult
  if (!workresult || !user?.id) return false

  return (
    (workresult.operator_by1 === user.id && !workresult.operator_at1) ||
    (workresult.operator_by2 === user.id && !workresult.operator_at2) ||
    (workresult.operator_by3 === user.id && !workresult.operator_at3)
  )
})

const approveworkresult = async () => {
  const workresult = props.report?.workresult
  if (!workresult) return

  const confirm = await Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda akan menyetujui data ini.",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, setujui",
    cancelButtonText: "Batal",
  })

  if (!confirm.isConfirmed) return

  try {
    const res = await axios.post(route("workresult.approve"), {
      id: workresult.id,
    })

    const now = new Date().toISOString()
    if (workresult.operator_by1 === user.id) workresult.operator_at1 = now
    if (workresult.operator_by2 === user.id) workresult.operator_at2 = now
    if (workresult.operator_by3 === user.id) workresult.operator_at3 = now

    Swal.fire({
      icon: "success",
      title: res.data.message || "Berhasil disetujui!",
      timer: 1500,
      showConfirmButton: false,
    })
  } catch (error) {
    console.error(error)
    Swal.fire({
      icon: "error",
      title: "Gagal approve!",
      text: error.response?.data?.message || error.message || "Terjadi kesalahan saat menyetujui.",
    })
  }
}

const currentSection = ref('report');

const fetch = async (section = 'report', report) => {
  const reportId = typeof report === 'object' ? report.id : report;

  if (!reportId) {
    console.error('Report tidak ditemukan');
    return;
  }

  Swal.showLoading();

  try {
    const response = await axios.post(
      route('working-reports.fetch', reportId),
      { section }
    );
    const data = response.data;
    Swal.close();

    switch (section) {
      case 'report':
        form.first_section = data.first_section;
        break;
      case 'checksheetday':
        form1.checksheetday = data.checksheetday;
        break;
      case 'warmingup':
        form3.warmingup = data.warmingup;
        break;
      case 'upload':
        form5.upload = data.upload;
        break;
      case 'workresult':
        form4.workresult = data.workresult;
        break;
    }

    form.report = data.report;
    currentSection.value = section;
  } catch (error) {
    console.error(error);
    Swal.close();
  }
};

const shouldShowGroup = (item, index) => {
  if (index === 0) return true;
  return form1.results[index - 1].group_name !== item.group_name;
};

const getGroupCount = (groupName) => {
  return form1.results.filter(i => i.group_name === groupName).length;
};

const isLastInGroup = (index) => {
  const current = form1.results[index];
  const next = form1.results[index + 1];

  return !next || next.group_name !== current.group_name;
};

const hasCheckSheet = computed(() => !!props.checksheetworkresult)

const hasWorkResultAccess = computed(() => {
  return !!props.checksheetworkresult && !!props.upload
})


onMounted(() => {
  form.reported_date = new Date().toISOString().slice(0, 10);
});

const formatDate = (date) => {
  if (!date) return '-'
  return new Date(date).toOnlyIndonesianDate()
}

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>

  <DashboardLayout :title="__('Working Order')">
    <Card class="border bg-white dark:bg-white dark:text-black">
      <!-- <template #header>
        <div class="flex items-center justify-between p-1 bg-gradient-to-r from-white-600 to-white-400 px-4 text-white shadow">
          <div class="flex items-center">
            <Link :href="route('working-reports.index')">
              <Button class="bg-gray-700 hover:bg-gray-900 rounded-md float-left text-xs"> <Icon name="caret-left" /> <p class="font-bold"> | Kembali </p></Button>
            </Link>
          </div>

          <div class="flex-1 text-center">
            <p class="font-bold  text-gray-900 dark:text-gray-100 text-sm">
              <span
                    :class="[
                    'px-2 py-1 rounded-full text-[10px] font-bold',
                    {
                        'bg-gray-100 text-gray-800': report.status === 'draft',
                        'bg-yellow-100 text-yellow-800': report.status === 'checksheet_done',
                        'bg-orange-100 text-orange-800': report.status === 'warming_up_done',
                        'bg-blue-100 text-blue-800': report.status === 'photo_uploaded',
                        'bg-green-100 text-green-800': report.status === 'selesai',
                    },
                    ]"
                >
                    {{
                        report.status === 'draft'
                            ? 'Proses Draft'
                            : report.status === 'checksheet_done'
                            ? 'Proses Checksheet'
                            : report.status === 'warming_up_done'
                            ? 'Proses Warming Up'
                            : report.status === 'photo_uploaded'
                            ? 'Proses Upload'
                            : report.status === 'selesai'
                            ? 'Selesai'
                            : report.status
                    }}
                </span>
            </p>
          </div>

          <div class="flex items-center justify-end">
            <Button class="bg-red-700 text-white-800 px-4 py-2 rounded-md hover:bg-red-500 float-end"> <Icon name="warning" class="w-6 h-4"/><p class="font-bold text-xs"> | Lapor  </p></Button>
          </div>
        </div>
      </template> -->

      <template #body>
      <div class="flex flex-col space-y-2 p-1">
        <div class="flex flex-col space-y-2 p-1">
          <div class="flex flex-row overflow-x-auto md:overflow-x-visible space-x-0.5 w-full rounded">
            <!-- <div class="border border-black p-1 w-full" :class="{ 'bg-blue-600': currentSection === 'report' }">
              <a
                href="#list-report" id="list-report-list" data-toggle="list" role="tab" aria-controls="report" class="list-group-item list-group-item-action active"
                @click.prevent="fetch('report', report)"
              >
                <p :class="{ 'text-white': currentSection === 'report' }">Working Order</p>
              </a>
            </div> -->
            <div
              class="border rounded p-1 text-center text-sm flex-1 flex-shrink"
              :class="{
                  'bg-blue-700 text-white font-bold': currentSection === 'report',
                  'bg-blue-500 text-white font-bold': currentSection !== 'report' && report.id,
                  'bg-white text-black': currentSection !== 'report' && !report.id
              }"
              @click.prevent="fetch('report', report)"
            >
              <a href="#list-report" id="list-report-list" role="tab" aria-controls="report">
                <p class="text-[11px] font-bold">Working Order</p>
              </a>
            </div>

            <!-- <div class="border rounded p-1 w-full" :class="{ 'bg-blue-500': currentSection === 'checksheetday' }">
              <a
                href="#list-checksheetday" id="list-checksheetday-list" data-toggle="list" role="tab" aria-controls="checksheetday"  class="list-group-item list-group-item-action d-flex justify-content-between"
                :class="! report.sectionFiveOpen ? 'disabled' : ''"
                @click.prevent="fetch('checksheetday', report)"
              >
                <p :class="{ 'text-white': currentSection === 'checksheetday' }">Check Sheet
                </p>
              </a>
            </div> -->

            <div
              class="border rounded p-1 text-center text-sm flex-1 flex-shrink"
              :class="{
                  'bg-blue-700 text-white font-bold': currentSection === 'checksheetday',
                  'bg-blue-500 text-white font-bold': currentSection !== 'checksheetday' && report.checksheetday?.checksheetworkresult?.id,
                  'bg-white text-black': currentSection !== 'checksheetday' && !report.checksheetday?.checksheetworkresult?.id
              }"
              @click.prevent="fetch('checksheetday', report)"
            >
              <a href="#list-checksheetday" id="list-checksheetday-list" role="tab" aria-controls="checksheetday" :class="! report.sectionFiveOpen ? 'disabled' : ''">
                <p class="text-[11px] font-bold">Check Sheet</p>
              </a>
            </div>

            <!-- <div class="border rounded p-1 w-full" :class="{ 'bg-blue-500': currentSection === 'warmingup' }">
              <a
                href="#list-warmingup" id="list-warmingup-list" data-toggle="list" role="tab" aria-controls="warmingup"  class="list-group-item list-group-item-action d-flex justify-content-between"
                :class="! report.sectionFiveOpen ? 'disabled' : ''"
                @click.prevent="fetch('warmingup', report)"
              >
                <p :class="{ 'text-white': currentSection === 'warmingup' }">Warming Up
                </p>
              </a>
            </div>             -->

            <div
              class="border rounded p-1 text-center text-sm flex-1 flex-shrink"
              :class="{
                  'bg-blue-700 text-white font-bold': currentSection === 'warmingup',
                  'bg-blue-500 text-white font-bold': currentSection !== 'warmingup' && report.warmingup?.id,
                  'bg-white text-black': currentSection !== 'warmingup' && !report.warmingup?.id
              }"
              @click.prevent="fetch('warmingup', report)"
            >
              <a href="#list-warmingup" id="list-warmingup-list" role="tab" aria-controls="warmingup" :class="! report.sectionFiveOpen ? 'disabled' : ''">
                <p class="text-[11px] font-bold">Warming Up</p>
              </a>
            </div>

            <!-- <div class="border rounded p-1 w-full" :class="{ 'bg-blue-500': currentSection === 'upload' }">
              <a
                href="#list-upload" id="list-upload-list" data-toggle="list" role="tab" aria-controls="upload"  class="list-group-item list-group-item-action d-flex justify-content-between"
                :class="! report.sectionFiveOpen ? 'disabled' : ''"
                @click.prevent="fetch('upload', report)"
              >
                <p :class="{ 'text-white': currentSection === 'upload' }">Upload Foto
                </p>
              </a>
            </div> -->

            <div
              class="border rounded p-1 text-center text-sm flex-1 flex-shrink"
              :class="{
                'bg-blue-700 text-white font-bold': currentSection === 'upload' && hasCheckSheet,
                'bg-blue-500 text-white font-bold': currentSection !== 'upload' && report.upload?.id && hasCheckSheet,
                'bg-gray-200 text-black cursor-not-allowed': !hasCheckSheet,
                'bg-white text-black': currentSection !== 'upload' && !report.upload?.id && hasCheckSheet
              }"
              @click.prevent="hasCheckSheet && fetch('upload', report)"
            >
              <a href="#list-upload" id="list-upload-list" role="tab" aria-controls="upload" :class="!report.sectionFiveOpen || !hasCheckSheet "
              >
                <div class="flex items-center justify-center space-x-0.5 md:space-x-1">
                  <Icon v-if="!hasCheckSheet" name="lock" class="w-3 h-4 md:w-3 md:h-3 text-black" />
                  <p class="text-[11px] font-bold">Upload</p>
                </div>
              </a>
            </div>

            <!-- <div class="border rounded p-1 w-full" :class="{ 'bg-blue-500': currentSection === 'workresult' }">
              <a
                href="#list-workresult" id="list-workresult-list" data-toggle="list" role="tab" aria-controls="workresult"  class="list-group-item list-group-item-action d-flex justify-content-between"
                :class="! report.sectionFiveOpen ? 'disabled' : ''"
                @click.prevent="fetch('workresult', report)"
              >
                <p>Work Result
                </p>
              </a>
            </div> -->

            <div
              class="border rounded p-1 text-center text-sm flex-1 flex-shrink"
               :class="{
                  'bg-blue-700 text-white font-bold': currentSection === 'workresult',
                  'bg-blue-500 text-white font-bold': currentSection !== 'workresult' && report.workresult?.id && hasWorkResultAccess,
                  'bg-gray-200 text-black cursor-not-allowed': !hasWorkResultAccess
                }"
                @click.prevent="hasWorkResultAccess && fetch('workresult', report)"
            >
              <a href="#list-workresult" id="list-workresult-list" role="tab" aria-controls="workresult" :class="!hasWorkResultAccess ">
                <div class="flex items-center justify-center space-x-0.5 md:space-x-1">
                  <Icon v-if="!hasWorkResultAccess" name="lock" class="w-3 h-4 md:w-3 md:h-3 text-black" />
                  <p class="text-[11px] font-bold">Work Result</p>
                </div>
              </a>
            </div>
            <!-- END -->

          </div>
        </div>

        <div class="flex flex-col md:flex-row mt-2 md:space-y-0 md:space-x-0">
          <div class=" p-2 w-full" id="list-section1" role="tabpanel" aria-labelledby="list-section1-list">
          <div class="flex flex-col space-y-2">

            <!-- section working report -->
					  <div v-if="currentSection === 'report'" class="tab-pane fade show active" id="list-report" role="tabpanel" aria-labelledby="list-report-list">
            <!-- <div v-if="! deviation.first_section.ampr_responded_at" class="tab-pane fade show active p-2" id="list-section1" role="tabpanel" aria-labelledby="list-section1-list"> -->
            <!-- <div class="tab-pane fade show active p-2" id="list-section1" role="tabpanel" aria-labelledby="list-section1-list"> -->
              <div class="flex flex-col space-y-4 p-2">

                <div class="flex flex-col items-start space-y-1">
                  <label for="machine_id" class="font-bold text-xs">
                    {{ __('Nama Mesin') }}
                  </label>

                    <div class="w-full">
                      <Select
                        v-model="form.machine_id"
                        :options="machines.map(machine => ({
                          label: `${machine.name} - ${machine.type} - ${machine.nomor} - ${machine.no_sarana} (${machine.region.name})`,
                          value: machine.id,
                        }))"
                        :searchable="true"
                        placeholder="Pilih Mesin"
                        style="font-size: 0.7rem;"
                        disabled
                      />
                    <InputError :error="form.errors.machine_id" />
                    </div>
                </div>

                <div class="flex flex-col items-start space-y-1">
                    <label for="region_id" class="font-bold text-xs">
                      {{ __('Nama Wilayah') }}
                    </label>

                      <div class="w-full">
                        <Select
                          v-model="form.region_id"
                          :options="regions.map(region => ({
                            label: region.name,
                            value: region.id,
                          }))"
                          :searchable="true"
                          placeholder="Pilih Wilayah"
                          style="font-size: 0.7rem;"
                          disabled
                        />

                    <InputError :error="form.errors.region_id"/>
                  </div>
                </div>

                <div class="flex flex-col items-start space-y-1">
                    <label for="date" class="font-bold text-xs">
                      {{ __('Tanggal') }}
                    </label>

                      <div class="w-full">
                        <Input
                          v-model="form.date"
                          :placeholder="__('Tanggal')"
                          type="datetime-local"
                          class="text-xs"
                          disabled
                        />
                  <InputError :error="form.errors.date"/>
                  </div>
                </div>

              </div>
            </div>
            <!-- section working report -->

            <!-- section checksheetday -->
            <div v-if="currentSection === 'checksheetday'" class="tab-pane fade" id="list-checksheetday" role="tabpanel" aria-labelledby="list-checksheetday-list">
              <div class="flex flex-col space-y-4 p-2" v-if="currentStep === 1">

                <!-- Bagian Informasi Mesin -->
                <!-- <div class="grid grid-cols-2 gap-4 mb-4"> -->
                <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                  <div class="flex flex-col items-start space-y-1">
                    <label for="machine_id" class="font-bold text-xs">
                    {{ __('Nama Mesin') }}
                    </label>

                      <div class="w-full">
                        <Select
                          v-model="form.machine_id"
                          :options="machines.map(machine => ({
                            label: `${machine.name} - ${machine.type} - ${machine.nomor} - ${machine.no_sarana} (${machine.region.name})`,
                            value: machine.id,
                          }))"
                          :searchable="true"
                          placeholder="Pilih Mesin"
                          style="font-size: 0.7rem;"
                          disabled
                        />
                      <InputError :error="form.errors.machine_id" />
                      </div>
                  </div>

                  <!-- <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Jenis / Tipe KPJR</label>
                    <Input v-model="form1.jenis" :placeholder="__('Jenis / Tipe KPJR')" type="text" class="w-full text-xs" />
                    <InputError :error="form1.errors.jenis" />
                  </div> -->

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Hari / Tanggal</label>
                    <Input v-model="form1.tanggal" required type="date" class="w-full text-xs"/>
                    <InputError :error="form1.errors.tanggal" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Jam Mesin</label>
                    <Input v-model="form1.jam_mesin" :placeholder="__('Jam Mesin')" required type="time" step="1" class="w-full text-xs" />
                    <InputError :error="form1.errors.jam_mesin" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Lokasi Pelaksanaan</label>
                    <Input v-model="form1.lokasi" required type="text" :placeholder="__('Lokasi Pelaksanaan')" class="w-full text-xs"/>
                    <InputError :error="form1.errors.lokasi" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Counter Pecok</label>
                    <Input v-model="form1.counter_pecok" required type="number" :placeholder="__('Counter Pecok')" class="w-full text-xs"/>
                    <InputError :error="form1.errors.counter_pecok" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Wilayah Resort</label>
                    <Input v-model="form1.wilayah" required type="text" :placeholder="__('Wilayah Resort')" class="w-full text-xs"/>
                    <InputError :error="form1.errors.wilayah" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Kilometer Mesin</label>
                    <Input v-model="form1.kilometer_mesin" required type="number" :placeholder="__('Kilometer Mesin')" class="w-full text-xs"/>
                    <InputError :error="form1.errors.kilometer_mesin" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Daop / Divre</label>

                    <div class="w-full text-xs">
                    <Select
                      v-model="form1.region_id"
                      :options="regions.map(region => ({
                        label: region.name,
                        value: region.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Daop / Divre"
                      required
                      style="font-size: 0.7rem;"
                    />
                    <InputError :error="form1.errors.region_id" />
                    </div>
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">No Seri</label>
                    <Input v-model="form1.no_seri" required type="number" :placeholder="__('No Seri')" class="w-full text-xs"/>
                    <InputError :error="form1.errors.no_seri" />
                  </div>

                </div>

                <div class="flex justify-end mt-3 w-full text-xs">
                <!-- <div v-if="isComplianceMember || isMpm" class="d-flex justify-content-end mt-3"> -->
                  <!-- <Button v-if="isSpkp && ! deviation.sixth_section?.spkp_signed_at" @click.prevent="approveSixthSection('spkp')" class="bg-blue-700 hover:bg-blue-900 rounded-md float-right">Paraf SPV Penyimpangan</Button>
                  <Button v-if="isAmpr && ! deviation.sixth_section?.ampr_signed_at && deviation.sixth_section?.spkp_signed_at" @click.prevent="approveSixthSection('ampr')" class="bg-blue-700 hover:bg-blue-900 rounded-md float-right">Paraf Asman Pemenuhan Regulasi</Button>
                  <Button v-if="isMpm && ! deviation.sixth_section?.mpm_signed_at && deviation.sixth_section?.ampr_signed_at" @click.prevent="approveSixthSection('mpm')" class="bg-blue-700 hover:bg-blue-900 rounded-md float-right">Paraf Manager Pemastian Mutu</Button> -->
                  <Button v-if="!report.checksheetday?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="submitchecksheetday()">Simpan</Button>
                  <Button v-if="report.checksheetday?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="updatechecksheetday()">Edit</Button>
                  <Button v-if="report.checksheetday?.id || showNextButton" class="bg-blue-700 hover:bg-blue-900 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep = 2" > Lanjut → </Button>
                </div>
              </div>

              <!-- Tabel Unit Komponen -->
              <div v-else-if="currentStep === 2">
                <div class="overflow-x-auto">
                  <table class="table-auto border-collapse border border-black w-full min-w-full text-[10px] md:text-xs">
                    <thead class="bg-gray-300 text-black">
                      <tr>
                        <th colspan="11" class="border border-black px-1 py-1 text-center bg-gray-600 font-bold text-white">{{ groups[currentGroupIndex] }}</th>
                      </tr>
                      <tr>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">No</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Komponen</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Rujukan</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Cek</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Tambah</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Ganti</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Nilai Rujukan</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Kr/Dpn</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Kn/Dpn</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Sat.</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">App.</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="(item, index) in currentGroupResults" :key="index">
                        <td class="border border-black px-2 py-1 text-center">{{ item.urutan }}</td>
                        <td class="border border-black px-2 py-1 text-left">{{ item.komponen }}</td>
                        <td class="border border-black px-2 py-1">{{ item.rujukan }}</td>
                        <td class="border border-black px-2 py-1 text-center">
                          <input type="checkbox" :checked="item.cek == 1" @change="toggleResult(item, 'cek')" />
                        </td>
                        <td class="border border-black px-2 py-1 text-center">
                          <input type="checkbox" :checked="item.tambahan == 1" @change="toggleResult(item, 'tambahan')"/>
                        </td>
                        <td class="border border-black px-2 py-1 text-center">
                          <input type="checkbox" :checked="item.ganti == 1" @change="toggleResult(item, 'ganti')"/>
                        </td>
                        <td class="border border-black px-2 py-1 text-center">{{ item.nilai_rujukan }}</td>
                        <td class="border border-black p-0 m-0 relative">
                          <input
                            v-model="item.kiri_depan"
                            type="text"
                            placeholder="...."
                            class="absolute inset-0 w-full h-full border-none focus:ring-0 text-center text-[10px] p-0 m-0"
                            @change="saveTextField(item)"/>
                        </td>
                        <td class="border border-black p-0 m-0 relative">
                          <input
                            v-model="item.kanan_depan"
                            type="text"
                            placeholder="...."
                            class="absolute inset-0 w-full h-full border-none focus:ring-0 text-center text-[10px] p-0 m-0"
                            @change="saveTextField(item)" />
                        </td>
                        <td class="border border-black px-2 py-1 text-center text-[10px] p-0 m-0">{{ item.satuan }}</td>
                        <td class="border border-black p-0 m-0 relative">
                          <input
                            v-model="item.keterangan"
                            type="text"
                            placeholder="...."
                            @change="saveTextField(item)"
                            class="absolute inset-0 w-full h-full border-none focus:ring-0 text-center text-[10px] p-0 m-0"
                          />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="flex justify-between mt-4">
                    <Button class="bg-gray-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="prevGroup"> ← Kembali </Button>

                    <Button class="bg-blue-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="nextGroup"> Lanjut → </Button>
                  </div>
                  <br>
                </div>
                </div>

                <div v-else-if="currentStep === 3">
                  <table class="w-full table-auto">
                    <tfoot>
                      <tr class="flex flex-col lg:flex-row"> <td colspan="9" class="border border-black align-top px-2 py-2 font-semibold text-sm w-full lg:w-1/2">
                              Catatan riwayat gangguan :
                              <textarea
                                  v-model="form2.catatan_gangguan"
                                  class="w-full border-none text-sm resize-none mt-1 p-1"
                                  rows="5"
                                  placeholder="Tuliskan catatan riwayat gangguan di sini..."
                                  @change="saveWorkResult"
                              ></textarea>
                          </td>

                          <td colspan="4" class="border border-black align-top px-0 py-0 w-full lg:w-1/2">
                              <table class="w-full border-collapse">
                                  <thead>
                                      <tr>
                                          <th colspan="5" class="border border-black bg-gray-200 text-center font-bold py-1 text-sm">
                                              Hasil Kerja:
                                          </th>
                                      </tr>
                                      <tr class="bg-gray-100 text-center font-semibold text-xs">
                                          <th colspan="3" class="border border-black px-1 py-1 w-[70%]">Lokasi dan Jam beroperasi</th>
                                          <th class="border border-black px-1 py-1 w-[15%]">hu / hi</th>
                                          <th class="border border-black px-1 py-1 w-[15%]">Jumlah</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                          <td colspan="3" class="border border-black px-1 py-0 text-left">
                                              <input
                                                  v-model="form2.lokasi_dan_jam1"
                                                  type="text"
                                                  class="w-full border-none text-xs p-0.5"
                                                  placeholder="Isi lokasi dan jam beroperasi"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                          <td class="border border-black px-1 py-0 text-center">
                                              <input
                                                  v-model="form2.hu_hi_1"
                                                  type="text"
                                                  placeholder="...."
                                                  class="w-full border-none text-center text-xs p-0.5"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                          <td class="border border-black px-1 py-0 text-center">
                                              <input
                                                  v-model="form2.jumlah_1"
                                                  type="text"
                                                  placeholder="...."
                                                  class="w-full border-none text-center text-xs p-0.5"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>
                                      <tr>
                                          <td colspan="3" class="border border-black px-1 py-0 text-left">
                                              <input
                                                  v-model="form2.lokasi_dan_jam2"
                                                  type="text"
                                                  class="w-full border-none text-xs p-0.5"
                                                  placeholder="Isi lokasi dan jam beroperasi"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                          <td class="border border-black px-1 py-0 text-center">
                                              <input
                                                  v-model="form2.hu_hi_2"
                                                  type="text"
                                                  placeholder="...."
                                                  class="w-full border-none text-center text-xs p-0.5"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                          <td class="border border-black px-1 py-0 text-center">
                                              <input
                                                  v-model="form2.jumlah_2"
                                                  type="text"
                                                  placeholder="...."
                                                  class="w-full border-none text-center text-xs p-0.5"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>
                                      <tr>
                                          <td colspan="3" class="border border-black px-1 py-0 text-left">
                                              <input
                                                  v-model="form2.lokasi_dan_jam3"
                                                  type="text"
                                                  class="w-full border-none text-xs p-0.5"
                                                  placeholder="Isi lokasi dan jam beroperasi"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                          <td class="border border-black px-1 py-0 text-center">
                                              <input
                                                  v-model="form2.hu_hi_3"
                                                  type="text"
                                                  placeholder="...."
                                                  class="w-full border-none text-center text-xs p-0.5"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                          <td class="border border-black px-1 py-0 text-center">
                                              <input
                                                  v-model="form2.jumlah_2"
                                                  type="text"
                                                  placeholder="...."
                                                  class="w-full border-none text-center text-xs p-0.5"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>

                                      <tr class="bg-gray-200 text-center font-bold text-xs">
                                          <td colspan="2" class="border border-black py-1">Operator</td>
                                          <td class="border border-black py-1">Paraf</td>
                                          <td colspan="2" class="border border-black py-1">Validasi</td>
                                      </tr>

                                      <tr class="bg-gray-200 text-center font-semibold text-xs">
                                          <td colspan="2" class="border border-black text-left w-64">
                                              <Select
                                                  v-model="form2.operator_by1"
                                                  :options="users.filter(user => user.id !== 1).map(user => ({
                                                      label: user.name,
                                                      value: user.id,
                                                  }))"
                                                  :searchable="true"
                                                  placeholder="Pilih"
                                                  class="w-full border-none text-center text-xs"
                                                  style="font-size: 0.7rem;"
                                                  @change="saveWorkResult"
                                              >
                                                <template #option="{ option }">
                                                  <span class="text-xs antialiased">
                                                      {{ option.label }}
                                                  </span>
                                                </template>
                                              </Select>
                                          </td>
                                          <td class="border border-black text-center text-xs py-1">
                                              Disetujui Pada : <br>
                                              {{ formatDate(report.checksheetday?.checksheetworkresult?.operator_at1) }}
                                          </td>
                                          <td colspan="2" class="border border-black text-center p-0 m-0 relative">
                                              <input
                                                  v-model="form2.validasi1"
                                                  type="text"
                                                  class="absolute inset-0 w-full h-full border-none text-center text-[10px] p-0 m-0"
                                                  placeholder="...."
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>

                                      <tr class="bg-gray-200 text-center font-semibold text-xs">
                                          <td colspan="2" class="border border-black text-left w-64">
                                              <Select
                                                  v-model="form2.operator_by2"
                                                  :options="users.filter(user => user.id !== 1).map(user => ({
                                                      label: user.name,
                                                      value: user.id,
                                                  }))"
                                                  :searchable="true"
                                                  placeholder="Pilih"
                                                  class="w-full border-none text-center text-xs"
                                                  style="font-size: 0.7rem;"
                                                  @change="saveWorkResult"
                                              >
                                                <template #option="{ option }">
                                                  <span class="text-xs antialiased">
                                                      {{ option.label }}
                                                  </span>
                                                </template>
                                              </Select>
                                          </td>
                                          <td class="border border-black text-center text-xs py-1">
                                              Disetujui Pada : <br>
                                              {{ formatDate(report.checksheetday?.checksheetworkresult?.operator_at2) }}
                                          </td>
                                          <td colspan="2" class="border border-black text-center p-0 m-0 relative">
                                              <input
                                                  v-model="form2.validasi2"
                                                  type="text"
                                                  class="absolute inset-0 w-full h-full border-none text-center text-[10px] p-0 m-0"
                                                  placeholder="...."
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>

                                      <tr class="bg-gray-200 text-center font-semibold text-xs">
                                          <td colspan="2" class="border border-black text-left w-64">
                                              <Select
                                                  v-model="form2.operator_by3"
                                                  :options="users.filter(user => user.id !== 1).map(user => ({
                                                      label: user.name,
                                                      value: user.id,
                                                  }))"
                                                  :searchable="true"
                                                  placeholder="Pilih"
                                                  class="w-full border-none text-center text-xs"
                                                  style="font-size: 0.7rem;"
                                                  @change="saveWorkResult"
                                              >
                                                <template #option="{ option }">
                                                  <span class="text-xs antialiased">
                                                      {{ option.label }}
                                                  </span>
                                                </template>
                                              </Select>
                                          </td>
                                          <td class="border border-black text-center text-xs py-1">
                                              Disetujui Pada : <br>
                                              {{ formatDate(report.checksheetday?.checksheetworkresult?.operator_at3) }}
                                          </td>
                                          <td colspan="2" class="border border-black text-center p-0 m-0 relative">
                                              <input
                                                  v-model="form2.validasi3"
                                                  type="text"
                                                  class="absolute inset-0 w-full h-full border-none text-center text-[10px] p-0 m-0"
                                                  placeholder="...."
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>

                                      <tr class="bg-gray-200 text-center font-semibold text-xs">
                                          <td colspan="2" class="border border-black text-left w-64">
                                              <Select
                                                  v-model="form2.operator_by4"
                                                  :options="users.filter(user => user.id !== 1).map(user => ({
                                                      label: user.name,
                                                      value: user.id,
                                                  }))"
                                                  :searchable="true"
                                                  placeholder="Pilih"
                                                  class="w-full border-none text-center text-xs"
                                                  style="font-size: 0.7rem;"
                                                  @change="saveWorkResult"
                                              >
                                                <template #option="{ option }">
                                                  <span class="text-xs antialiased">
                                                      {{ option.label }}
                                                  </span>
                                                </template>
                                              </Select>
                                          </td>
                                          <td class="border border-black text-center text-xs py-1">
                                              Disetujui Pada : <br>
                                              {{ formatDate(report.checksheetday?.checksheetworkresult?.operator_at4) }}
                                          </td>
                                          <td colspan="2" class="border border-black text-center p-0 m-0 relative">
                                              <input
                                                  v-model="form2.validasi4"
                                                  type="text"
                                                  class="absolute inset-0 w-full h-full border-none text-center text-[10px] p-0 m-0"
                                                  placeholder="...."
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>

                                  </tbody>
                              </table>
                          </td>
                      </tr>
                    </tfoot>
                  </table>

                  <div class="d-flex justify-content-end mt-3">
                      <Button v-if="!report.checksheetday?.checksheetworkresult?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="submitchecksheetworkresult()">Simpan</Button>
                      <Button v-if="report.checksheetday?.checksheetworkresult?.id" class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="updatechecksheetworkresult()">Edit</Button>
                      <Button v-if="canApprove" class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs" @click.prevent="approvechecksheetworkresult()">Approve</Button>
                      <Button v-if="report.checksheetday?.id || currentStep === 2" class="bg-gray-700 hover:bg-gray-900 px-4 py-1 rounded text-xs " @click="currentStep = 2" > ← Kembali</Button>
                  </div>

              </div>
						</div>
            <!-- section checksheetday -->

            <!-- section warmingup -->
            <div v-if="currentSection === 'warmingup'" class="tab-pane fade p-2" id="list-warmingup" role="tabpanel" aria-labelledby="list-warmingup-list">
							<div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                <div class="flex flex-col items-start space-y-1">
                  <label for="machine_id"  class="font-bold text-xs">
                    {{ __('Nama Mesin') }}
                  </label>

                  <Select
                    v-model="form.machine_id"
                    :options="machines.map(machine => ({
                      label: `${machine.name} - ${machine.type} - ${machine.nomor} - ${machine.no_sarana} (${machine.region.name})`,
                      value: machine.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Mesin"
                    style="font-size: 0.7rem;"
                    disabled
                  />

                  <InputError :error="form.errors.machine_id"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="waktu_start_engine" class="font-bold text-xs">
                    {{ __('Waktu Start Engine') }}
                  </label>

                  <Input
                    v-model="form3.waktu_start_engine"
                    :placeholder="__('Waktu Start Engine')"
                    type="datetime-local"
                    class="text-xs"
                  />

                  <InputError :error="form3.errors.waktu_start_engine"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_kerja" class="font-bold text-xs">
                    {{ __('Jam Kerja') }}
                  </label>

                  <Input
                    v-model="form3.jam_kerja"
                    :placeholder="__('Jam Kerja')"
                    type="time"
                    step="1"
                    class="text-xs"
                  />

                  <InputError :error="form3.errors.jam_kerja"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_mesin" class="font-bold text-xs">
                    {{ __('Jam Mesin') }}
                  </label>

                  <Input
                    v-model="form3.jam_mesin"
                    :placeholder="__('Jam Mesin')"
                    type="time"
                    step="1"
                    class="text-xs"
                  />

                  <InputError :error="form3.errors.jam_mesin"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_genset" class="font-bold text-xs">
                    {{ __('Jam Genset') }}
                  </label>

                  <Input
                    v-model="form3.jam_genset"
                    :placeholder="__('Jam Genset')"
                    type="time"
                    step="1"
                    class="text-xs"
                  />

                  <InputError :error="form3.errors.jam_genset"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="counter_pecok" class="font-bold text-xs">
                    {{ __('Counter Pecok') }}
                  </label>

                  <Input
                    v-model="form3.counter_pecok"
                    :placeholder="__('Counter Pecok')"
                    type="number"
                    class="text-xs"
                  />

                  <InputError :error="form3.errors.counter_pecok"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="oddometer" class="font-bold text-xs">
                    {{ __('Oddo Meter') }}
                  </label>

                  <Input
                    v-model="form3.oddometer"
                    :placeholder="__('Oddo Meter')"
                    type="number"
                    class="text-xs"
                  />

                  <InputError :error="form3.errors.oddometer"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="waktu_stop_engine" class="font-bold text-xs">
                    {{ __('Waktu Stop Engine') }}
                  </label>

                  <Input
                    v-model="form3.waktu_stop_engine"
                    :placeholder="__('Waktu Stop Engine')"
                    type="datetime-local"
                    class="text-xs"
                  />

                  <InputError :error="form3.errors.waktu_stop_engine"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="penggunaan_hsd" class="font-bold text-xs">
                    {{ __('Penggunaan HSD') }}
                  </label>

                  <Input
                    v-model="form3.penggunaan_hsd"
                    :placeholder="__('Penggunaan HSD')"
                    type="number"
                    step="0.01"
                    class="text-xs"
                  />

                  <InputError :error="form3.errors.penggunaan_hsd"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="hsd_tersedia" class="font-bold text-xs">
                    {{ __('HSD Tersedia') }}
                  </label>

                  <Input
                    v-model="form3.hsd_tersedia"
                    :placeholder="__('HSD Tersedia')"
                    type="number"
                    step="0.01"
                    class="text-xs"
                  />

                  <InputError :error="form3.errors.hsd_tersedia"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="user_id" class="font-bold text-xs">
                    {{ __('Crew') }}
                  </label>

                  <Select
                    v-model="form3.user_id"
                    :options="users.filter(user => user.id !== 1).map(user => ({
                      label: user.name,
                      value: user.id,
                    }))"
                    :searchable="true"
                    mode="tags"
                    placeholder="Pilih Crew"
                    required
                    style="font-size: 0.5rem;"
                  />

                  <InputError :error="form3.errors.user_id"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="note" class="font-bold text-xs">
                    {{ __('Keterangan') }}
                  </label>

                  <TextArea
                    v-model="form3.note"
                    :placeholder="__('Keterangan')"
                    type="text"
                    class="text-xs"
                  />
                  <InputError :error="form3.errors.note"/>
                </div>
              </div>

							<div class="d-flex justify-content-end mt-3">
								<Button v-if="!report.warmingup?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="submitwarmingup()">Simpan</Button>
                <Button v-else class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="updatewarmingup()">Edit</Button>
							</div>
						</div>
            <!-- section warmingup -->

            <!-- section upload -->
					  <div v-if="currentSection === 'upload'" class="tab-pane fade show active p-1" id="list-upload" role="tabpanel" aria-labelledby="list-upload-list">

              <div class="flex flex-col space-y-4 p-1">

                <div class="flex flex-col items-start space-y-1">
                    <label for="date" class="font-bold text-xs">
                      {{ __('Tanggal') }}
                    </label>

                      <div class="w-full">
                        <Input
                          v-model="form5.date"
                          :placeholder="__('Tanggal')"
                          type="date"
                          class="text-xs"
                          @change="autosaveUpload"
                        />
                  <InputError :error="form.errors.date"/>
                  </div>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <AttachmentInline
                    :model="upload ?? {}"
                    type="Upload"
                    :redaction="`Lampiran Upload Foto Silang`"
                  />
                </div>

              </div>
            </div>
            <!-- section upload -->

            <!-- section workresult -->
            <div v-if="currentSection === 'workresult'" class="tab-pane fade p-2" id="list-workresult" role="tabpanel" aria-labelledby="list-warmingup-list">

              <div v-if="currentStep1 === 1 " class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                <div class="flex flex-col items-start space-y-1">
                  <label for="machine_id" class="font-bold text-xs">
                      {{ __('Nama Mesin') }}
                  </label>
                  <Select
                    v-model="form.machine_id"
                    :options="machines.map(machine => ({
                        label: `${machine.name} - ${machine.type} - ${machine.nomor} - ${machine.no_sarana} (${machine.region.name})`,
                        value: machine.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Mesin"
                    style="font-size: 0.7rem;"
                    disabled
                  />
                  <InputError
                      :error="form4.errors.machine_id"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="tanggal" class="font-bold text-xs">
                  {{ __('Hari/Tanggal') }}
                  </label>
                  <Input
                    v-model="form4.tanggal"
                    :placeholder="__('Hari/Tanggal')"
                    type="datetime-local"
                    class="w-full text-xs"
                  />
                  <InputError :error="form4.errors.tanggal" />
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="cuaca" class="font-bold text-xs">
                      {{ __('Cuaca') }}
                  </label>
                  <Input
                    v-model="form4.cuaca"
                    :placeholder="__('cuaca')"
                    type="text"
                    class="w-full text-xs"
                  />
                  <InputError :error="form4.errors.cuaca" />
                </div>

                <div></div>
              </div>

              <div v-if="currentStep1 === 1" class="flex justify-end mt-4 w-full text-xs">
                  <Button class="bg-blue-700 hover:bg-blue-900 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep1 = 2" > Lanjut → </Button>
              </div>

              <div v-if="currentStep1 === 2 " class="grid grid-cols-1 gap-1 mb-1 md:grid-cols-1">
                <div class="font-bold rounded-md text-xs">A. DATA PEKERJAAN</div><br>
                <div  class="grid grid-cols-1 gap-4 mb-1 md:grid-cols-1">
                  <div class="flex flex-col items-start space-y-1">
                    <label for="wilayah" class="font-bold text-xs">
                      {{ __('Wilayah resor') }}
                    </label>

                      <Input
                        v-model="form4.wilayah"
                        :placeholder="__('Wilayah resor')"
                        type="text"
                        class="w-full text-xs"
                      />

                    <InputError :error="form4.errors.wilayah" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="petak_jalan" class="font-bold text-xs">
                      {{ __('Petak Jalan') }}
                    </label>

                      <Input
                        v-model="form4.petak_jalan"
                        :placeholder="__('Petak Jalan')"
                        type="text"
                        class="w-full text-xs"
                      />

                    <InputError :error="form4.errors.petak_jalan" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jalur" class="font-bold text-xs">
                      {{ __('Lokasi Stabling Awal') }}
                    </label>

                      <Input
                        v-model="form4.jalur"
                        :placeholder="__('Lokasi Stabling Awal')"
                        type="text"
                        class="w-full text-xs"
                      />
                    <InputError :error="form4.errors.jalur" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jalur" class="font-bold text-xs">
                      {{ __('Lokasi Stabling Akhir') }}
                    </label>

                      <Input
                        v-model="form4.jalur"
                        :placeholder="__('Lokasi Stabling Akhir')"
                        type="text"
                        class="w-full text-xs"
                      />
                    <InputError :error="form4.errors.jalur" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="kelas_jalan" class="font-bold text-xs">
                      {{ __('Kelas Jalan') }}
                    </label>

                      <Input
                        v-model="form4.kelas_jalan"
                        :placeholder="__('Kelas Jalan')"
                        type="text"
                        class="w-full text-xs"
                      />

                    <InputError :error="form4.errors.kelas_jalan" />
                  </div>
                </div>

                <div class="space-y-2">
                  <!-- Baris 1 -->
                  <div class="flex flex-col items-start space-y-1">
                    <label class="md:col-span-2 text-xs font-bold">Lokasi (Km/Hm)</label>
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full">
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <Input v-model="form4.lokasi_awal1" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                        <span class="text-center text-xs">s/d</span>
                        <Input v-model="form4.lokasi_akhir1" :placeholder="__('Hu/Hi')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                        <span class=" text-xs">(Hu/Hi)</span>
                      </div>

                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Jumlah (Km/Hm)</span>
                        <span>:</span>
                        <Input v-model="form4.jumlah1" :placeholder="__('Jumlah Km/Hm')" type="text" class="dot-input w-28 text-right text-xs" />
                        <span class="text-xs">M'sp</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris 2 -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full">
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <Input v-model="form4.lokasi_awal2" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                        <span class="text-center text-xs">s/d</span>
                        <Input v-model="form4.lokasi_akhir2" :placeholder="__('Hu/Hi')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                        <span class=" text-xs">(Hu/Hi)</span>
                      </div>

                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Jumlah (Km/Hm)</span>
                        <span>:</span>
                        <Input v-model="form4.jumlah2" :placeholder="__('Jumlah Km/Hm')" type="text" class="dot-input w-28 text-right text-xs" />
                        <span class="text-xs">M'sp</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris 3 -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full">
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <Input v-model="form4.lokasi_awal3" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                        <span class="text-center text-xs">s/d</span>
                        <Input v-model="form4.lokasi_akhir3" :placeholder="__('Hu/Hi')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                        <span class=" text-xs">(Hu/Hi)</span>
                      </div>

                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Jumlah (Km/Hm)</span>
                        <span>:</span>
                        <Input v-model="form4.jumlah3" :placeholder="__('Jumlah Km/Hm')" type="text" class="dot-input w-28 text-right text-xs" />
                        <span class="text-xs">M'sp</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris Total -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full">
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                      </div>

                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Total (Km/Hm)</span>
                        <span>:</span>
                        <Input v-model="form4.total_distance" :placeholder="__('Total Km/Hm')" type="text" class="dot-input w-28 text-right text-xs" />
                        <span class="text-xs">M'sp</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="space-y-2">
                  <!-- Baris 1 -->
                  <div class="flex flex-col items-start space-y-1">
                    <label class="md:col-span-2 text-xs font-bold">No Wesel</label>
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full">
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <Input v-model="form4.no_wesel1" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                        <span class="text-center text-xs">Km/Hm : </span>
                        <Input v-model="form4.km_hm1" :placeholder="__('Hu/Hi')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                        <span class=" text-xs">(Hu/Hi)</span>
                      </div>

                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Jumlah Wesel</span>
                        <span>:</span>
                        <Input v-model="form4.jumlah_wesel1" :placeholder="__('Jumlah Wesel')" type="text" class="dot-input w-28 text-right text-xs" />
                        <span class="text-xs">Unit</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris 2 -->
                  <div class="flex flex-col items-start space-y-1">
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full">
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <Input v-model="form4.no_wesel2" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                        <span class="text-center text-xs">Km/Hm : </span>
                        <Input v-model="form4.km_hm2" :placeholder="__('Hu/Hi')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                        <span class=" text-xs">(Hu/Hi)</span>
                      </div>

                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Jumlah Wesel</span>
                        <span>:</span>
                        <Input v-model="form4.jumlah_wesel2" :placeholder="__('Jumlah Wesel')" type="text" class="dot-input w-28 text-right text-xs" />
                        <span class="text-xs">Unit</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris 3 -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full">
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <Input v-model="form4.no_wesel3" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                        <span class="text-center text-xs">Km/Hm : </span>
                        <Input v-model="form4.km_hm3" :placeholder="__('Hu/Hi')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                        <span class=" text-xs">(Hu/Hi)</span>
                      </div>

                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Jumlah Wesel</span>
                        <span>:</span>
                        <Input v-model="form4.jumlah_wesel3" :placeholder="__('Jumlah Wesel')" type="text" class="dot-input w-28 text-right text-xs" />
                        <span class="text-xs">Unit</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris Total -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full">
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                      </div>

                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Total Wesel</span>
                        <span>:</span>
                        <Input v-model="form4.total_wesel" :placeholder="__('Total Wesel')" type="text" class="dot-input w-28 text-right text-xs" />
                        <span class="text-xs">Unit</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="flex justify-between mt-4">
                  <Button class="bg-gray-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep1 = 1"> ← Kembali </Button>
                  <Button class="bg-blue-700 hover:bg-blue-900 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep1 = 3" > Lanjut → </Button>
                </div>
              </div>

              <div v-if="currentStep1 === 3" class="tab-pane fade show active p-1">

                <div class="space-y-4 mb-6 text-sm">

                    <div class="flex flex-col space-y-4 p-1">
                        <div class="flex flex-col items-start space-y-1">
                            <AttachmentInline
                                :model="workresult ?? {}"  
                                type="WorkResult"
                                :redaction="`Lampiran Upload Foto Silang`"
                            />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <p class="font-bold text-xs">1. Data Opname Resor Jalan Rel (Awal):</p>

                        <div class="flex justify-between items-center text-xs ml-3">
                            <label for="mg1_awal" class="flex-1">a. MG 1 (Lurusan)</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.mg1_lurusan_awal" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Ada</span>
                                </label>
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.mg1_lurusan_awal_na" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Tidak ada</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-xs ml-3">
                            <label for="mg2_awal" class="flex-1">b. MG 2 (Lengkung)</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.mg2_lengkung_awal" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Ada</span>
                                </label>
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.mg2_lengkung_awal_na" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Tidak ada</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-xs ml-3">
                            <label for="mg3_awal" class="flex-1">c. MG 3 (Wesel)</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.mg3_wesel_awal" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Ada</span>
                                </label>
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.mg3_wesel_awal_na" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Tidak ada</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-300">

                    <div class="space-y-2">
                        <p class="font-bold text-xs">2. Data Pemeriksaan Silang</p>

                        <div class="flex justify-between items-center text-xs ml-3">
                            <label for="kpjr" class="flex-1">a. KPJR</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.kpjr" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Ada</span>
                                </label>
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.kpjr_na" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Tidak ada</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-xs ml-3">
                            <label for="lahan" class="flex-1">b. Lahan</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.lahan" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Ada</span>
                                </label>
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.lahan_na" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Tidak ada</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-300">

                    <div class="space-y-2">
                        <p class="font-bold text-xs">3. Data Perekaman (Awal)</p>
                        <div class="flex justify-between items-center text-xs ml-3">
                            <label for="perekaman_awal" class="flex-1">Status Perekaman Awal</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.perekaman_awal" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Ada</span>
                                </label>
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.perekaman_awal_na" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Tidak ada</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-300">

                    <div class="space-y-2">
                        <p class="font-bold text-xs">4. Data Opname Resor Jalan Rel (Akhir):</p>

                        <div class="flex justify-between items-center text-xs ml-3">
                            <label for="mg1_akhir" class="flex-1">a. MG 1 (Lurusan)</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.mg1_lurusan_akhir" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Ada</span>
                                </label>
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.mg1_lurusan_akhir_na" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Tidak ada</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-xs ml-3">
                            <label for="mg2_akhir" class="flex-1">b. MG 2 (Lengkung)</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.mg2_lengkung_akhir" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Ada</span>
                                </label>
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.mg2_lengkung_akhir_na" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Tidak ada</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-xs ml-3">
                            <label for="mg3_akhir" class="flex-1">c. MG 3 (Wesel)</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.mg3_wesel_akhir" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Ada</span>
                                </label>
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.mg3_wesel_akhir_na" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Tidak ada</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-300">

                    <div class="space-y-2">
                        <p class="font-bold text-xs">5. Data Perekaman (Akhir)</p>
                        <div class="flex justify-between items-center text-xs ml-3">
                            <label for="perekaman_akhir" class="flex-1">Status Perekaman Akhir</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.perekaman_akhir" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Ada</span>
                                </label>
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" v-model="form3.perekaman_akhir_na" :true-value="true" :false-value="false" class="rounded text-blue-600 focus:ring-blue-500 w-3 h-3">
                                    <span>Tidak ada</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-4 w-full">
                    <Button class="bg-gray-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep1 = 2"> ← Kembali </Button>
                    <Button class="bg-blue-700 hover:bg-blue-900 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep1 = 4" > Lanjut → </Button>
                </div>
              </div>

              <div v-if="currentStep1 === 4 " class="grid grid-cols-1 gap-4 mb-2 md:grid-cols-2">
                <div class="font-bold rounded-md text-xs">B. DATA OPERASI MESIN</div><br>

                <!-- <div class="grid grid-cols-2 gap-1 mb-4 text-sm"> -->
                  <div class="flex flex-col items-start space-y-1">
                    <label for="waktu_start_engine" class="font-bold text-xs">
                      {{ __('waktu Start Engine') }}
                    </label>

                    <Input
                      v-model="form4.waktu_start_engine"
                      :placeholder="__('waktu Start Engine')"
                      type="datetime-local"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.waktu_start_engine" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="waktu_stop_engine" class="font-bold text-xs">
                      {{ __('Waktu Stop Engine') }}
                    </label>

                    <Input
                      v-model="form4.waktu_stop_engine"
                      :placeholder="__('Waktu Stop Engine')"
                      type="datetime-local"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.waktu_stop_engine" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_luncuran" class="font-bold text-xs">
                      {{ __('Jam Travelling Awal') }}
                    </label>

                    <Input
                      v-model="form4.jam_travelling_awal"
                      :placeholder="__('Jam Travelling Awal')"
                      type="time"
                      step="1"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.jam_luncuran" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_luncuran" class="font-bold text-xs">
                      {{ __('Jam Travelling Akhir') }}
                    </label>

                    <Input
                      v-model="form4.jam_luncuran"
                      :placeholder="__('Jam Travelling Akhir')"
                      type="time"
                      step="1"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.jam_luncuran" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_luncuran" class="font-bold text-xs">
                      {{ __('Jam Kerja Awal') }}
                    </label>

                    <Input
                      v-model="form4.jam_luncuran"
                      :placeholder="__('Jam Kerja Awal')"
                      type="time"
                      step="1"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.jam_luncuran" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_luncuran" class="font-bold text-xs">
                      {{ __('Jam Kerja Akhir') }}
                    </label>

                    <Input
                      v-model="form4.jam_luncuran"
                      :placeholder="__('Jam Kerja Akhir')"
                      type="time"
                      step="1"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.jam_luncuran" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_luncuran" class="font-bold text-xs">
                      {{ __('Jam Mesin Awal') }}
                    </label>

                    <Input
                      v-model="form4.jam_luncuran"
                      :placeholder="__('Jam Mesin Awal')"
                      type="time"
                      step="1"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.jam_luncuran" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_luncuran" class="font-bold text-xs">
                      {{ __('Jam Mesin Akhir') }}
                    </label>

                    <Input
                      v-model="form4.jam_luncuran"
                      :placeholder="__('Jam Mesin Akhir')"
                      type="time"
                      step="1"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.jam_luncuran" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_luncuran" class="font-bold text-xs">
                      {{ __('Jam Generator Awal') }}
                    </label>

                    <Input
                      v-model="form4.jam_luncuran"
                      :placeholder="__('Jam Generator Awal')"
                      type="time"
                      step="1"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.jam_luncuran" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_luncuran" class="font-bold text-xs">
                      {{ __('Jam Generator Akhir') }}
                    </label>

                    <Input
                      v-model="form4.jam_luncuran"
                      :placeholder="__('Jam Generator Akhir')"
                      type="time"
                      step="1"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.jam_luncuran" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="counter_pecok" class="font-bold text-xs">
                      {{ __('Counter Tamping') }}
                    </label>

                    <Input
                      v-model="form4.counter_pecok"
                      :placeholder="__('Counter Tamping')"
                      type="number"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.counter_pecok" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="counter_pecok" class="font-bold text-xs">
                      {{ __('Oddometer') }}
                    </label>

                    <Input
                      v-model="form4.counter_pecok"
                      :placeholder="__('Oddometer')"
                      type="number"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.counter_pecok" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="counter_pecok" class="font-bold text-xs">
                      {{ __('HSD Awal Kerja') }}
                    </label>

                    <Input
                      v-model="form4.counter_pecok"
                      :placeholder="__('HSD Awal Kerja')"
                      type="number"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.counter_pecok" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="counter_pecok" class="font-bold text-xs">
                      {{ __('HSD Akhr Kerja') }}
                    </label>

                    <Input
                      v-model="form4.counter_pecok"
                      :placeholder="__('HSD Akhr Kerja')"
                      type="number"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.counter_pecok" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="counter_pecok" class="font-bold text-xs">
                      {{ __('Konsumsi HSD') }}
                    </label>

                    <Input
                      v-model="form4.counter_pecok"
                      :placeholder="__('Konsumsi HSD')"
                      type="number"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.counter_pecok" />
                  </div>
                <!-- </div> -->
                <div></div>

                <div class="col-span-full flex justify-between mt-4">
                  <Button class="bg-gray-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep1 = 3"> ← Kembali </Button>
                  <Button class="bg-blue-700 hover:bg-blue-900 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep1 = 5" > Lanjut → </Button>
                </div>

              </div>

              <div v-if="currentStep1 === 5 " class="grid grid-cols-1 gap-4 mb-2 md:grid-cols-2">
                <div class="font-bold rounded-md text-xs">C. DATA PERSONEL</div><br>

                <div class="flex flex-col items-start space-y-1">
                  <label for="pengawal_id" class="font-bold text-xs">
                    {{ __('Pengawal') }}
                  </label>

                  <Select
                    v-model="form4.pengawal_id"
                    :options="users.filter(user => user.id !== 1).map(user => ({
                      label: user.name,
                      value: user.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Pengawal"
                    style="font-size: 0.7rem;"
                  />
                  <InputError :error="form4.errors.pengawal_id" />
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="pengawal_id" class="font-bold text-xs">
                    {{ __('Pengawal') }}
                  </label>

                  <Select
                    v-model="form4.pengawal_id"
                    :options="users.filter(user => user.id !== 1).map(user => ({
                      label: user.name,
                      value: user.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Pengawal"
                    style="font-size: 0.7rem;"
                  />
                  <InputError :error="form4.errors.pengawal_id" />
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="operator_by1" class="font-bold text-xs">
                    {{ __('Operator 1') }}
                  </label>

                  <Select
                    v-model="form4.operator_by1"
                    :options="users.filter(user => user.id !== 1).map(user => ({
                      label: user.name,
                      value: user.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Operator"
                    style="font-size: 0.7rem;"
                    />
                  <InputError :error="form4.errors.operator_by1" />
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="operator_by2" class="font-bold text-xs">
                    {{ __('Operator 2') }}
                  </label>

                    <Select
                      v-model="form4.operator_by2"
                      :options="users.filter(user => user.id !== 1).map(user => ({
                        label: user.name,
                        value: user.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Operator"
                      style="font-size: 0.7rem;"
                    />
                    <InputError :error="form4.errors.operator_by2" />
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="operator_by2" class="font-bold text-xs">
                    {{ __('Operator 2') }}
                  </label>

                  <Select
                    v-model="form4.operator_by2"
                    :options="users.filter(user => user.id !== 1).map(user => ({
                      label: user.name,
                      value: user.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Operator"
                    style="font-size: 0.7rem;"
                  />

                  <InputError :error="form4.errors.operator_by2" />
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="operator_by3" class="font-bold text-xs">
                    {{ __('Operator 3') }}
                  </label>

                      <Select
                        v-model="form4.operator_by3"
                        :options="users.filter(user => user.id !== 1).map(user => ({
                          label: user.name,
                          value: user.id,
                        }))"
                        :searchable="true"
                        placeholder="Pilih Operator"
                        style="font-size: 0.7rem;"
                      />

                    <InputError :error="form4.errors.operator_by3" />
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="note" class="font-bold text-xs">
                    {{ __('Keterangan') }}
                  </label>

                  <TextArea
                    v-model="form4.note"
                    :placeholder="__('Keterangan')"
                    type="text"
                    class="w-full text-xs"
                  />
                  <InputError :error="form4.errors.note" />
                </div>


                <div class="col-span-full flex justify-between mt-4">
                  <Button class="bg-gray-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep1 = 4"> ← Kembali </Button>
                  <Button v-if="!report.workresult?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="submitworkresult()">Simpan</Button>
                  <Button v-if="report.workresult?.id" class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="updateworkresult()">Edit</Button>
                  <Button v-if="canApproveWorkResult" class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="approveworkresult()">Approve</Button>
                </div>
              </div>
            </div>
            <!-- section workresult -->

          </div>
          </div>
        </div>
        </div>
      </template>
    </Card>

  </DashboardLayout>
</template>
