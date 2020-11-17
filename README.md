<table>
  
Method | URI | Description 
  |---|:---:|---:|
GET	| /webtoon | 웹툰 조회API

GET	|	/webtoon/{webtoonId | 웹툰 상세 조회 API

GET	/webtoon/{webtoonId}/episode/{episodeId}	웹툰 회차별 상세조회 API

POST	/comment	댓글 등록 API

GET	/comment	댓글 조회 API

DELETE	/comment	댓글 삭제 API

POST	/like	댓글 좋아요 등록/취소 API

POST	/heart	하트 등록/취소 API

POST	/interest	관심 등록/취소  API

POST	/notice	알림 등록/취소 API

POST	/storage	임시저장 API

GET	/storage	임시저장 조회 API

GET	/storage/webtoon/{webtoonId}	임시저장 상세 조회 API

DELETE	/storage/webtoon/{webtoonId}/episode/{episodeId}	임시저장 삭제 API

GET	/interested	관심웹툰 조회API

POST	/auto-login	자동 로그인 API

POST	/nave-login	네이버로그인 API

GET	/recently-view	최근 본 웹툰 조회 API

GET	/recommendation	추천완결 API

GET	/recommendation/top10	추천완결 TOP 10 API

GET	/recommendation/interested	추천완결 관심웹툰 API

GET	/advertising	광고 API

GET	/search	검색 API

DELETE	/storage/webtoon/{webtoonId}/expiration	임시저장 만료삭제 API

POST	/episode	에피소드 추가 API   (FCM을 위한 임시)
