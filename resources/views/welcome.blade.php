<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UniNotes - Every lecture, every course, notes you can trust</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=public-sans:400,500,600,700|source-serif-4:400,500,600,700" rel="stylesheet" />
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body { background: #FBF8F3; color: #1B2A4A; font-family: "Public Sans", system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
    a { color: #8A1C24; text-decoration: none; }
    a:hover { color: #6E141B; }
    ::selection { background: #8A1C24; color: #FBF8F3; }

    @media (max-width: 768px) {
      /* Nav: drop the in-page jump links, keep logo + primary actions */
      nav a[href="#how"], nav a[href="#features"], nav a[href="#courses"] { display: none !important; }
      nav[style] { padding: 14px 20px !important; }
      nav > div[style*="gap: 34px"] { gap: 14px !important; }

      /* All the fixed multi-column grids collapse to one column */
      [style*="grid-template-columns: 1.05fr 0.95fr"],
      [style*="grid-template-columns: repeat(4, 1fr)"],
      [style*="grid-template-columns: repeat(3, 1fr)"],
      [style*="grid-template-columns: repeat(2, 1fr)"] {
        grid-template-columns: 1fr !important;
      }

      /* Section padding: 40px horizontal is too much on a narrow screen */
      [style*="padding: 88px 40px"],
      [style*="padding: 96px 40px"],
      [style*="padding: 36px 40px"],
      [style*="padding: 48px 40px"] {
        padding-left: 20px !important;
        padding-right: 20px !important;
      }

      /* Oversized headline/heading font sizes */
      h1[style*="font-size: 60px"] { font-size: 34px !important; }
      h2[style*="font-size: 42px"] { font-size: 28px !important; }
      h2[style*="font-size: 46px"] { font-size: 30px !important; }

      /* Hero illustration: fixed-height absolute-positioned cards don't
         fit a narrow viewport, so drop it and let the hero text lead */
      #top { grid-template-columns: 1fr !important; }
      #top > div[style*="position: relative"] { display: none !important; }
    }
  </style>
</head>
<body>

<div style="width: 100%; overflow: hidden; background: rgb(251, 248, 243);">

  <header style="position: sticky; top: 0px; z-index: 50; background: rgba(251, 248, 243, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(27, 42, 74, 0.1);">
    <nav style="max-width: 1200px; margin: 0px auto; padding: 18px 40px; display: flex; align-items: center; justify-content: space-between;">
      <a href="#top" style="display: flex; align-items: center; gap: 11px; color: rgb(27, 42, 74);">
        <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border-radius: 7px; font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 19px;">U</span>
        <span style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 21px; letter-spacing: -0.01em;">UniNotes</span>
      </a>
      <div style="display: flex; align-items: center; gap: 34px;">
        <a href="#how" style="color: rgb(58, 71, 98); font-size: 15px; font-weight: 500;">How it works</a>
        <a href="#features" style="color: rgb(58, 71, 98); font-size: 15px; font-weight: 500;">Features</a>
        <a href="#courses" style="color: rgb(58, 71, 98); font-size: 15px; font-weight: 500;">Courses</a>
        <a href="{{ route('login') }}" style="color: rgb(58, 71, 98); font-size: 15px; font-weight: 500;">Log in</a>
        <a href="{{ route('browse.index') }}" style="background: rgb(138, 28, 36); color: rgb(251, 248, 243); padding: 10px 20px; border-radius: 8px; font-size: 15px; font-weight: 600;">Browse notes</a>
      </div>
    </nav>
  </header>

  <section id="top" style="max-width: 1200px; margin: 0px auto; padding: 88px 40px 64px; display: grid; grid-template-columns: 1.05fr 0.95fr; gap: 64px; align-items: center;">
    <div>
      <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(138, 28, 36, 0.08); color: rgb(138, 28, 36); padding: 7px 14px; border-radius: 100px; font-size: 13px; font-weight: 600; letter-spacing: 0.02em; margin-bottom: 26px;">
        <span style="width: 7px; height: 7px; border-radius: 50%; background: rgb(138, 28, 36); display: inline-block;"></span>
        Built for students, by students
      </div>
      <h1 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 60px; line-height: 1.04; letter-spacing: -0.02em; color: rgb(27, 42, 74); text-wrap: balance;">Every lecture, every course &mdash; notes you can trust.</h1>
      <p style="margin-top: 24px; font-size: 19px; line-height: 1.6; color: rgb(58, 71, 98); max-width: 500px;">UniNotes is the shared library where students find peer-reviewed study notes, search by course and semester, and share what they've learned with others.</p>
      <div style="display: flex; gap: 14px; margin-top: 34px; flex-wrap: wrap;">
        <a href="{{ route('browse.index') }}" style="background: rgb(138, 28, 36); color: rgb(251, 248, 243); padding: 15px 28px; border-radius: 9px; font-size: 16px; font-weight: 600;">Browse notes</a>
        <a href="{{ auth()->check() ? route('notes.create') : route('register') }}" style="background: transparent; color: rgb(27, 42, 74); padding: 15px 28px; border-radius: 9px; font-size: 16px; font-weight: 600; border: 1.5px solid rgba(27, 42, 74, 0.2);">Upload your notes</a>
      </div>
    </div>

    <div style="position: relative; height: 440px;">
      <div style="position: absolute; top: 24px; left: 26px; width: 280px; background: rgb(255, 255, 255); border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 14px; padding: 20px; box-shadow: rgba(27, 42, 74, 0.28) 0px 18px 40px -18px; transform: rotate(-4deg);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
          <span style="font-size: 12px; font-weight: 700; color: rgb(138, 28, 36); letter-spacing: 0.04em;">ECON 201</span>
          <span style="font-size: 11px; color: rgb(136, 170, 153);">PDF &middot; 12p</span>
        </div>
        <div style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 17px; color: rgb(27, 42, 74); margin-bottom: 6px;">Elasticity &amp; Market Demand</div>
        <div style="height: 8px; background: rgba(27, 42, 74, 0.07); border-radius: 4px; margin-bottom: 7px;"></div>
        <div style="height: 8px; width: 80%; background: rgba(27, 42, 74, 0.07); border-radius: 4px; margin-bottom: 7px;"></div>
        <div style="height: 8px; width: 60%; background: rgba(27, 42, 74, 0.07); border-radius: 4px;"></div>
      </div>
      <div style="position: absolute; top: 150px; right: 6px; width: 290px; background: rgb(255, 255, 255); border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 14px; padding: 20px; box-shadow: rgba(27, 42, 74, 0.32) 0px 24px 50px -18px; transform: rotate(3deg); z-index: 2;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
          <span style="font-size: 12px; font-weight: 700; color: rgb(138, 28, 36); letter-spacing: 0.04em;">CS 101</span>
          <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 600; color: rgb(46, 125, 79);"><span style="width: 6px; height: 6px; border-radius: 50%; background: rgb(46, 125, 79);"></span>Verified</span>
        </div>
        <div style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 17px; color: rgb(27, 42, 74); margin-bottom: 6px;">Recursion &amp; Big-O Notation</div>
        <div style="height: 8px; background: rgba(27, 42, 74, 0.07); border-radius: 4px; margin-bottom: 7px;"></div>
        <div style="height: 8px; width: 88%; background: rgba(27, 42, 74, 0.07); border-radius: 4px; margin-bottom: 14px;"></div>
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid rgba(27, 42, 74, 0.08);">
          <span style="font-size: 12px; color: rgb(91, 104, 133);">&#9733; 4.9 &middot; 214 downloads</span>
          <span style="font-size: 12px; font-weight: 600; color: rgb(138, 28, 36);">+8 credits</span>
        </div>
      </div>
      <div style="position: absolute; bottom: 6px; left: 36px; width: 250px; background: rgb(27, 42, 74); color: rgb(251, 248, 243); border-radius: 14px; padding: 18px 20px; box-shadow: rgba(27, 42, 74, 0.4) 0px 18px 40px -18px; transform: rotate(-2deg);">
        <div style="font-size: 12px; opacity: 0.7; margin-bottom: 5px;">Your credit balance</div>
        <div style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 30px;">142 <span style="font-size: 14px; font-weight: 500; opacity: 0.7;">credits</span></div>
      </div>
    </div>
  </section>

  <section style="border-top: 1px solid rgba(27, 42, 74, 0.1); border-bottom: 1px solid rgba(27, 42, 74, 0.1); background: rgb(245, 241, 232);">
    <div style="max-width: 1200px; margin: 0px auto; padding: 36px 40px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
      @foreach ([
          ['value' => '12,400+', 'label' => 'Notes shared'],
          ['value' => '340', 'label' => 'Courses covered'],
          ['value' => '8,900+', 'label' => 'Active students'],
          ['value' => '4.8★', 'label' => 'Average note rating'],
      ] as $stat)
        <div style="text-align: center;">
          <div style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 38px; color: rgb(138, 28, 36); line-height: 1;">{{ $stat['value'] }}</div>
          <div style="margin-top: 8px; font-size: 14px; color: rgb(91, 104, 133); font-weight: 500;">{{ $stat['label'] }}</div>
        </div>
      @endforeach
    </div>
  </section>

  <section id="how" style="max-width: 1200px; margin: 0px auto; padding: 96px 40px;">
    <div style="text-align: center; max-width: 640px; margin: 0px auto 60px;">
      <div style="font-size: 13px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: rgb(138, 28, 36); margin-bottom: 14px;">How it works</div>
      <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 42px; line-height: 1.1; letter-spacing: -0.02em;">From lost in lecture to ready for the exam</h2>
    </div>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px;">
      @foreach ([
          ['num' => '1', 'title' => 'Search by course', 'body' => 'Type your course code and instantly see every set of notes shared for it.'],
          ['num' => '2', 'title' => 'Download what you need', 'body' => 'Every file is peer-reviewed and rated, so you grab the notes that actually cover what your professor tests.'],
          ['num' => '3', 'title' => 'Upload & earn credits', 'body' => 'Share your own notes to earn credits and help the next student in your course.'],
      ] as $step)
        <div style="background: rgb(255, 255, 255); border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 32px;">
          <div style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 11px; background: rgba(138, 28, 36, 0.09); color: rgb(138, 28, 36); font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 20px; margin-bottom: 22px;">{{ $step['num'] }}</div>
          <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 22px; margin-bottom: 10px;">{{ $step['title'] }}</h3>
          <p style="font-size: 15px; line-height: 1.6; color: rgb(91, 104, 133);">{{ $step['body'] }}</p>
        </div>
      @endforeach
    </div>
  </section>

  <section id="features" style="background: rgb(27, 42, 74); color: rgb(251, 248, 243);">
    <div style="max-width: 1200px; margin: 0px auto; padding: 96px 40px;">
      <div style="max-width: 640px; margin-bottom: 56px;">
        <div style="font-size: 13px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: rgb(232, 160, 165); margin-bottom: 14px;">Features</div>
        <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 42px; line-height: 1.1; letter-spacing: -0.02em;">Everything you need to study smarter</h2>
      </div>
      <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1px; background: rgba(251, 248, 243, 0.12); border: 1px solid rgba(251, 248, 243, 0.12); border-radius: 16px; overflow: hidden;">
        @foreach ([
            ['icon' => '&uarr;', 'title' => 'Upload in seconds', 'body' => 'Drag in a PDF, photo, or doc. We tag it to the right subject and make it searchable for everyone.'],
            ['icon' => '&#9906;', 'title' => 'Search by course', 'body' => 'Filter by semester, subject code, or topic to find exactly the notes you need.'],
            ['icon' => '&check;', 'title' => 'Verified quality', 'body' => 'Community ratings surface accurate, complete notes and flag the rest.'],
            ['icon' => '&#9670;', 'title' => 'Earn credits', 'body' => 'Contributors earn recognition with every download.'],
        ] as $feature)
          <div style="background: rgb(27, 42, 74); padding: 36px 34px;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 46px; height: 46px; border-radius: 11px; background: rgba(232, 160, 165, 0.14); color: rgb(232, 160, 165); font-size: 22px; margin-bottom: 20px;">{!! $feature['icon'] !!}</div>
            <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 22px; margin-bottom: 10px;">{{ $feature['title'] }}</h3>
            <p style="font-size: 15px; line-height: 1.6; color: rgba(251, 248, 243, 0.66);">{{ $feature['body'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section id="courses" style="max-width: 1200px; margin: 0px auto; padding: 96px 40px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 44px; flex-wrap: wrap; gap: 16px;">
      <div style="max-width: 560px;">
        <div style="font-size: 13px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: rgb(138, 28, 36); margin-bottom: 14px;">Popular right now</div>
        <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 42px; line-height: 1.1; letter-spacing: -0.02em;">The courses students are studying this week</h2>
      </div>
      <a href="{{ route('browse.index') }}" style="font-size: 15px; font-weight: 600; display: inline-flex; align-items: center; gap: 7px;">Browse all courses &rarr;</a>
    </div>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px;">
      @foreach ([
          ['code' => 'CS 101', 'title' => 'Intro to Computer Science', 'dept' => 'Computer Science', 'notes' => 248],
          ['code' => 'ECON 201', 'title' => 'Microeconomics', 'dept' => 'Economics', 'notes' => 196],
          ['code' => 'BIO 240', 'title' => 'Human Physiology', 'dept' => 'Biology', 'notes' => 173],
          ['code' => 'PSYC 110', 'title' => 'Foundations of Psychology', 'dept' => 'Psychology', 'notes' => 165],
          ['code' => 'MATH 220', 'title' => 'Linear Algebra', 'dept' => 'Mathematics', 'notes' => 141],
          ['code' => 'CHEM 130', 'title' => 'Organic Chemistry I', 'dept' => 'Chemistry', 'notes' => 128],
      ] as $course)
        <a href="{{ route('browse.index') }}" style="display: block; background: rgb(255, 255, 255); border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 15px; padding: 26px; color: rgb(27, 42, 74);">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <span style="font-size: 13px; font-weight: 700; color: rgb(138, 28, 36); letter-spacing: 0.03em;">{{ $course['code'] }}</span>
            <span style="font-size: 12px; color: rgb(91, 104, 133);">{{ $course['notes'] }} notes</span>
          </div>
          <div style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; margin-bottom: 8px; line-height: 1.2;">{{ $course['title'] }}</div>
          <div style="font-size: 14px; color: rgb(91, 104, 133);">{{ $course['dept'] }}</div>
        </a>
      @endforeach
    </div>
  </section>

  <section style="background: rgb(245, 241, 232); border-top: 1px solid rgba(27, 42, 74, 0.1);">
    <div style="max-width: 1200px; margin: 0px auto; padding: 96px 40px;">
      <div style="text-align: center; max-width: 640px; margin: 0px auto 56px;">
        <div style="font-size: 13px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: rgb(138, 28, 36); margin-bottom: 14px;">Loved on campus</div>
        <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 42px; line-height: 1.1; letter-spacing: -0.02em;">Students don't study alone anymore</h2>
      </div>
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
        @foreach ([
            ['quote' => "I went from scrambling before every midterm to walking in prepared. The verified notes basically saved my semester.", 'name' => 'Maya Okonkwo', 'meta' => 'Economics · Junior', 'initial' => 'M', 'color' => '#8A1C24'],
            ['quote' => "Uploading my CS notes earned me credits toward the exam-prep library. It genuinely pays to share.", 'name' => 'Daniel Reyes', 'meta' => 'Computer Science · Sophomore', 'initial' => 'D', 'color' => '#1B2A4A'],
            ['quote' => "Everything is tagged by subject and semester, so I'm never digging through a group chat for the right file again.", 'name' => 'Priya Nair', 'meta' => 'Biology · Senior', 'initial' => 'P', 'color' => '#C08A3E'],
        ] as $t)
          <figure style="background: rgb(255, 255, 255); border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 32px; display: flex; flex-direction: column; gap: 22px;">
            <blockquote style="font-family: 'Source Serif 4', serif; font-size: 19px; line-height: 1.5; color: rgb(27, 42, 74);">&ldquo;{{ $t['quote'] }}&rdquo;</blockquote>
            <figcaption style="display: flex; align-items: center; gap: 13px; margin-top: auto;">
              <span style="width: 42px; height: 42px; border-radius: 50%; background: {{ $t['color'] }}; display: inline-flex; align-items: center; justify-content: center; color: rgb(255, 255, 255); font-weight: 700; font-size: 16px; font-family: 'Source Serif 4', serif;">{{ $t['initial'] }}</span>
              <span>
                <span style="display: block; font-weight: 600; font-size: 15px; color: rgb(27, 42, 74);">{{ $t['name'] }}</span>
                <span style="display: block; font-size: 13px; color: rgb(91, 104, 133);">{{ $t['meta'] }}</span>
              </span>
            </figcaption>
          </figure>
        @endforeach
      </div>
    </div>
  </section>

  <section style="max-width: 1200px; margin: 0px auto; padding: 96px 40px;">
    <div style="background: rgb(138, 28, 36); border-radius: 24px; padding: 72px 48px; text-align: center; color: rgb(251, 248, 243); position: relative; overflow: hidden;">
      <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 46px; line-height: 1.1; letter-spacing: -0.02em; max-width: 640px; margin: 0px auto; text-wrap: balance;">Your next A is sitting in someone's notebook.</h2>
      <p style="margin: 20px auto 0px; font-size: 18px; line-height: 1.6; color: rgba(251, 248, 243, 0.82); max-width: 520px;">Join thousands of students sharing the notes that actually help. Free to browse, rewarding to contribute.</p>
      <div style="display: flex; gap: 14px; justify-content: center; margin-top: 36px; flex-wrap: wrap;">
        <a href="{{ route('browse.index') }}" style="background: rgb(251, 248, 243); color: rgb(138, 28, 36); padding: 15px 30px; border-radius: 9px; font-size: 16px; font-weight: 700;">Browse notes</a>
        <a href="{{ auth()->check() ? route('notes.create') : route('register') }}" style="background: rgba(251, 248, 243, 0.14); color: rgb(251, 248, 243); padding: 15px 30px; border-radius: 9px; font-size: 16px; font-weight: 600; border: 1px solid rgba(251, 248, 243, 0.3);">Upload your notes</a>
      </div>
    </div>
  </section>

  <footer style="border-top: 1px solid rgba(27, 42, 74, 0.1);">
    <div style="max-width: 1200px; margin: 0px auto; padding: 48px 40px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
      <div style="display: flex; align-items: center; gap: 11px;">
        <span style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border-radius: 6px; font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 17px;">U</span>
        <span style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 18px;">UniNotes</span>
      </div>
      <div style="display: flex; gap: 28px; flex-wrap: wrap;">
        <a href="#how" style="color: rgb(91, 104, 133); font-size: 14px; font-weight: 500;">How it works</a>
        <a href="#features" style="color: rgb(91, 104, 133); font-size: 14px; font-weight: 500;">Features</a>
        <a href="#courses" style="color: rgb(91, 104, 133); font-size: 14px; font-weight: 500;">Courses</a>
        <a href="{{ route('login') }}" style="color: rgb(91, 104, 133); font-size: 14px; font-weight: 500;">Log in</a>
      </div>
      <div style="font-size: 13px; color: rgb(138, 150, 174);">&copy; {{ date('Y') }} UniNotes</div>
    </div>
  </footer>
</div>

</body>
</html>
