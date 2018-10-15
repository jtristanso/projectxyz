
export default{
  routes: [{
    path: '/canales',
    name: 'canales',
    component: resolve => require(['modules/testForDeveloper/Canales.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/signup',
    name: 'signup',
    component: resolve => require(['modules/home/Signup.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/verification/:email',
    name: 'verification',
    component: resolve => require(['modules/home/Verification.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/login_verification/:username/:code',
    name: 'loginVerification',
    component: resolve => require(['modules/home/LoginByVerification.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/login',
    name: 'loginAccount',
    component: resolve => require(['modules/home/LogIn.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/home',
    name: 'landing',
    component: resolve => require(['modules/home/Landing.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/request_reset_password',
    name: 'requestResetPassword',
    component: resolve => require(['modules/home/RequestResetPassword.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/reset_password/:username/:code',
    name: 'resetPassword',
    component: resolve => require(['modules/home/ResetPassword.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: resolve => require(['modules/dashboard/Dashboard.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/semesters',
    name: 'semester',
    component: resolve => require(['modules/semester/Semester.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/courses/en',
    name: 'enrolledCourses',
    component: resolve => require(['modules/course/CourseViewer.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/courses',
    name: 'courses',
    component: resolve => require(['modules/course/Course.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/courses/accounts/:code',
    name: 'courseAccounts',
    component: resolve => require(['modules/course/CourseAccount.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/tests/ft/:code',
    name: 'testsForTeachers',
    component: resolve => require(['modules/test/TeacherTest.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/tests/fs/:code',
    name: 'testsForStudents',
    component: resolve => require(['modules/test/StudentTest.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/mchoice',
    name: 'mchoice',
    component: resolve => require(['modules/question/MultipleChoice.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/questions/ft/:code',
    name: 'questionsForTeachers',
    component: resolve => require(['modules/question/Question.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/tests/take/fs/:code',
    name: 'takeExam',
    component: resolve => require(['modules/take/TakeTest.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/account_settings',
    name: 'accountSettings',
    component: resolve => require(['modules/account/AccountSettings.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/accounts',
    name: 'accounts',
    component: resolve => require(['modules/account/Accounts.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/announcements/ft',
    name: 'announcementsForTeacher',
    component: resolve => require(['modules/announcement/Announcements.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/announcements/fs',
    name: 'announcementsForStudent',
    component: resolve => require(['modules/announcement/AnnouncementsStudent.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/attendance/ft/:code',
    name: 'attendanceFt',
    component: resolve => require(['modules/attendance/AttendanceViewer.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/reports/ft',
    name: 'reportsFt',
    component: resolve => require(['modules/report/Teacher.vue'], resolve),
    meta: {
      tokenRequired: true
    },
    props: (route) => ({
      code: route.query.code
    })
  },
  {
    path: '/reports/fs',
    name: 'reportsFs',
    component: resolve => require(['modules/report/Student.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/guide/fs',
    name: 'guideFs',
    component: resolve => require(['modules/guide/Student.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/guide/ft',
    name: 'guideFt',
    component: resolve => require(['modules/guide/Teacher.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/privacy_policy',
    name: 'privacyPolicy',
    component: resolve => require(['modules/docs/PrivacyPolicy.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/terms_and_conditions',
    name: 'termsAndConditions',
    component: resolve => require(['modules/docs/TermsAndConditions.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/discussions',
    name: 'discussions',
    component: resolve => require(['modules/discussion/Public.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/discussion_reports',
    name: 'discussionReports',
    component: resolve => require(['modules/discussion/Reports.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/calendar',
    name: 'calendar',
    component: resolve => require(['modules/calendar/Calendar.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/planner',
    name: 'planner',
    component: resolve => require(['modules/planner/Planner.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/organizations',
    name: 'organizations',
    component: resolve => require(['modules/organization/Organization.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/create_organization',
    name: 'createOrganization',
    component: resolve => require(['modules/organization/Create.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/tickets/:code',
    name: 'tickets',
    component: resolve => require(['modules/event/Ticket.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  }
  ]
}
