# 🔖 Bookmarker

**Bookmarker**는 로그인 기반의 개인 북마크 관리 웹 서비스입니다.
PHP와 MySQL을 이용해 사용자 인증, 북마크 CRUD 기능을 구현하고
실제 도메인 환경에 배포하여 운영까지 경험한 프로젝트입니다.

---

## 📌 주요 기능

* 회원가입 / 로그인 / 로그아웃
* 개인별 북마크 추가 / 조회 / 수정 / 삭제 (CRUD)
* 사용자별 데이터 접근 제어 (본인 데이터만 접근 가능)
* 실제 웹 호스팅 환경 배포

---

## 🛠 기술 스택

| 구분         | 기술                          |
| ---------- | --------------------------- |
| Frontend   | HTML, CSS                   |
| Backend    | PHP                         |
| Database   | MySQL (phpMyAdmin)          |
| Auth       | PHP Session                 |
| Security   | CSRF Token, Session Timeout |
| Deployment | dothome 웹호스팅                |

---

## 🔐 보안 설계

Bookmarker는 기본적인 웹 보안 위협을 고려하여 다음과 같은 방어 로직을 적용했습니다.

### 1️⃣ 인증(Authentication)

* `password_hash()` / `password_verify()` 사용
* 비밀번호 평문 저장 ❌

### 2️⃣ 인가(Authorization)

* 모든 북마크 조회 / 수정 / 삭제 시
  **user_id 기반 접근 제어**
* 타인의 북마크 ID로 직접 접근 시 차단

### 3️⃣ CSRF 방어

* 세션 기반 CSRF 토큰 생성
* 모든 POST 요청에서 토큰 검증
* 토큰 불일치 시 요청 거부 (403)

### 4️⃣ 세션 보안

* 활동 기반 세션 타임아웃 (30분)
* 일정 시간 비활동 시 자동 로그아웃
* 세션 탈취 피해 최소화

---

## 📂 프로젝트 구조

```
bookmarker/
 ├─ bootstrap.php    # 보안 초기화 (세션, CSRF, 타임아웃)
 ├─ db.php           # DB 연결
 ├─ header.php       # 공통 헤더 UI
 ├─ index.php        # 메인 페이지
 ├─ login.php        # 로그인
 ├─ register.php     # 회원가입
 ├─ mypage.php       # 북마크 목록
 ├─ edit.php         # 북마크 수정
 ├─ delete.php       # 북마크 삭제
 └─ logout.php       # 로그아웃
```

---

## 🌐 배포 주소

* **서비스 URL**
  👉 [https://bookmarker929.dothome.co.kr/bookmarker/](https://bookmarker929.dothome.co.kr/bookmarker/)

---

## 💡 프로젝트 의의

* 단순 기능 구현이 아닌 **실제 배포까지 경험**
* 인증(Authentication)과 인가(Authorization)를 분리하여 설계
* CSRF, 세션 타임아웃 등 **기본 웹 보안 개념을 코드로 구현**
* 이후 서비스 확장(태그, 검색, 공유 기능 등)이 가능한 구조

---

## 📈 향후 개선 아이디어

* 북마크 태그 기능
* 검색 및 정렬
* 로그인 실패 횟수 제한
* UI 공통 CSS 분리
* HTTPS 환경에서 Secure Cookie 옵션 적용

---

## 👤 개발자

* 개인 프로젝트
* PHP 기반 웹 서비스 학습 및 포트폴리오 목적

---

> **“PHP 기반 로그인 웹 서비스를 직접 구현하고,
> CSRF와 세션 보안까지 고려해 실제 도메인에 배포한 프로젝트입니다.”**

